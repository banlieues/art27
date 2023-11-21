<?php
namespace Phpdocx\Libs;

use Phpdocx\Logger\PhpdocxLogger;
/*
    This file is part of SAPP

    Simple and Agnostic PDF Parser (SAPP) - Parse PDF documents in PHP (and update them)
    Copyright (C) 2020 - Carlos de Alfonso (caralla76@gmail.com)

    This file is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This file is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

/**
 * This class is used to manage a buffer of characters. The main features are that
 *   it is possible to add data (by usign *data* function), and getting the current
 *   size. Then it is possible to get the whole buffer using function *get_raw*
 */
class Buffer {
    protected $_buffer = "";
    protected $_bufferlen = 0;

    public function __construct($string = null) {
        if ($string === null)
            $string = "";

        $this->_buffer = $string;
        $this->_bufferlen = strlen($string);
    }
    /**
     * Adds raw data to the buffer
     * @param data the data to add
     */
    public function data(...$datas) {
        foreach ($datas as $data) {
            $this->_bufferlen += strlen($data);
            $this->_buffer .= $data;
        }
    }    
    /**
     * Obtains the size of the buffer
     * @return size the size of the buffer
     */
    public function size() {
        return $this->_bufferlen;
    }
    /**
     * Gets the raw data from the buffer
     * @return buffer the raw data
     */
    public function get_raw() {
        return $this->_buffer;
    }
    /**
     * Appends buffer $b to this buffer
     * @param b the buffer to be added to this one
     * @return buffer this object
     */
    public function append($b) {
        if (get_class($b) !== get_class($this)) {
            PhpdocxLogger::logger('Invalid buffer to add to this one', 'fatal');
        }
        
        $this->_buffer .= $b->get_raw();
        $this->_bufferlen = strlen($this->_buffer);
        return $this;
    }
    /**
     * Obtains a new buffer that is the result from the concatenation of this buffer and the parameter
     * @param b the buffer to be added to this one
     * @return buffer the resulting buffer (different from this one)
     */
    public function add(...$bs) {
        foreach ($bs as $b) {
            if (get_class($b) !== get_class($this)) {
                PhpdocxLogger::logger('Invalid buffer to add to this one', 'fatal');
            }
        }

        $r = new Buffer($this->_buffer);
        foreach ($bs as $b)
            $r->append($b);

        return $r;
    }
    /**
     * Returns a new buffer that contains the same data than this one
     * @return buffer the cloned buffer
     */
    /*public function clone() {
        $buffer = new Buffer($this->_buffer);
        return $buffer;
    }*/
    /**
     * Provides a easy to read string representation of the buffer, using the "var_dump" output
     *   of the variable, but providing a reduced otput of the buffer
     * @return str a string with the representation of the buffer
     */
    public function __toString() {
        if (strlen($this->_buffer) < (__CONVENIENT_MAX_BUFFER_DUMP * 2))
            return debug_var($this);

        $buffer = $this->_buffer;
        $this->_buffer = substr($buffer, 0, __CONVENIENT_MAX_BUFFER_DUMP);
        $this->_buffer .= "\n...\n" . substr($buffer, -__CONVENIENT_MAX_BUFFER_DUMP);
        $result = debug_var($this);
        $this->_buffer = $buffer;
        return $result;
    }    


    public function show_bytes($columns, $offset = 0, $length = null) {
        if ($length === null)
            $length = $this->_bufferlen;

        $result = "";
        $length = min($length, $this->_bufferlen);
        for ($i = $offset; $i < $length;) {
            for ($j = 0; ($j < $columns) && ($i < $length); $i++, $j++) {
                $result .= sprintf("%02x ", ord($this->_buffer[$i]));
            }
            $result .= "\n";
        }

        return $result;
    }
}

class PDFDoc extends Buffer {

    // The PDF version of the parsed file
    protected $_pdf_objects = [];
    protected $_pdf_version_string = null;
    protected $_pdf_trailer_object = null;
    protected $_xref_position = 0;
    protected $_xref_table = [];
    protected $_max_oid = 0;
    protected $_buffer = "";    
    protected $_backup_state = [];
    protected $_certificate = null;
    protected $_appearance = null;

    // Array of pages ordered by appearance in the final doc (i.e. index 0 is the first page rendered; index 1 is the second page rendered, etc.)
    // Each entry is an array with the following fields:
    //  - id: the id in the document (oid); can be translated into <id> 0 R for references
    //  - info: an array with information about the page
    //      - size: the size of the page
    protected $_pages_info = [];

    // Gets a new oid for a new object
    protected function get_new_oid() {
        $this->_max_oid++;
        return $this->_max_oid;
    }
    
    /**
     * Retrieve the number of pages in the document (not considered those pages that could be added by the user using this object or derived ones)
     * @return pagecount number of pages in the original document
     */
    public function get_page_count() {
        return count($this->_pages_info);
    }

    /**
     * Function that backups the current objects with the objective of making temporary modifications, and to restore
     *   the state using function "pop_state". Many states can be stored, and they will be retrieved in reverse order
     *   using pop_state
     */
    public function push_state() {
        $cloned_objects = [];
        foreach ($this->_pdf_objects as $oid => $object) {
            $cloned_objects[$oid] = clone $object;
        }
        array_push($this->_backup_state, [ 'max_oid' => $this->_max_oid, 'pdf_objects' => $cloned_objects ]);
    }

    /**
     * Function that retrieves an stored state by means of function "push_state"
     * @return restored true if a previous state was restored; false if there was no stored state
     */
    public function pop_state() {
        if (count($this->_backup_state) > 0) {
            $state = array_pop($this->_backup_state);
            $this->_max_oid = $state['max_oid'];
            $this->_pdf_objects = $state['pdf_objects'];
            return true;
        }
        return false;
    }

    /**
     * The function parses a document from a string: analyzes the structure and obtains and object
     *   of type PDFDoc (if possible), or false, if an error happens.
     * @param buffer a string that contains the file to analyze
     */
    public static function from_string($buffer, $depth = null) {
        $structure = PDFUtilFnc::acquire_structure($buffer, $depth);
        if ($structure === false) {
            return false;    
        }

        $trailer = $structure["trailer"];
        $version = $structure["version"];
        $xref_table = $structure["xref"];
        $xref_position = $structure["xrefposition"];
        $revisions = $structure["revisions"];

        $pdfdoc = new PDFDoc();
        $pdfdoc->_pdf_version_string = $version;
        $pdfdoc->_pdf_trailer_object = $trailer;
        $pdfdoc->_xref_position = $xref_position;
        $pdfdoc->_xref_table = $xref_table;
        $pdfdoc->_xref_table_version = $structure["xrefversion"];
        $pdfdoc->_revisions = $revisions;
        $pdfdoc->_buffer = $buffer;

        $oids = array_keys($xref_table);
        sort($oids);
        $pdfdoc->_max_oid = array_pop($oids);

        $pdfdoc->_acquire_pages_info();

        return $pdfdoc;
    }

    public function get_revision($rev_i) {
        if ($rev_i === null)
            $rev_i = count($this->_revisions) - 1;
        if ($rev_i < 0)
            $rev_i = count($this->_revisions) + $rev_i - 1;

        return substr($this->_buffer, 0, $this->_revisions[$rev_i]);
    }

    /**
     * Function that builds the object list from the xref table
     */
    public function build_objects_from_xref() {
        foreach ($this->_xref_table as $oid => $obj) {
            $obj = $this->get_object($oid);
            $this->add_object($obj);
        }
    }

    /**
     * This function creates an interator over the objects of the document, and makes use of function "get_object".
     *   This mechanism enables to walk over any object, either they are new ones or they were in the original doc.
     *   Enables: 
     *         foreach ($doc->get_object_iterator() as $oid => obj) { ... }
     * @return oid=>obj the objects
     */
    public function get_object_iterator($allobjects = false) {
        if ($allobjects === true) {
            for ($i = 0; $i <= $this->_max_oid; $i++) {
                yield $i => $this->get_object($i);
            }
        } else {
            foreach ($this->_xref_table as $oid => $offset) {
                if ($offset === null) continue;

                $o = $this->get_object($oid);
                if ($o === false) continue;

                yield $oid => $o;
            }
        }
    }

    /**
     * This function checks whether the passed object is a reference or not, and in case that
     *   it is a reference, it returns the referenced object; otherwise it return the object itself
     * @param reference the reference value to obtain
     * @return obj it reference can be interpreted as a reference, the referenced object; otherwise, the object itself. 
     *   If the passed value is an array of references, it will return false
     */
    public function get_indirect_object( $reference ) {
        $object_id = $reference->get_object_referenced();
        if ($object_id !== false) {
            if (is_array($object_id))
                return false;
            return $this->get_object($object_id);
        }
        return $reference;
    }

    /**
     * Obtains an object from the document, usign the oid in the PDF document.
     * @param oid the oid of the object that is being retrieved
     * @param original if true and the object has been overwritten in this document, the object
     *                 retrieved will be the original one. Setting to false will retrieve the
     *                 more recent object
     * @return obj the object retrieved (or false if not found)
     */
    public function get_object($oid, $original_version = false) {
        if ($original_version === true) {
            // Prioritizing the original version
            $object = PDFUtilFnc::find_object($this->_buffer, $this->_xref_table, $oid);
            if ($object === false) {
                $object = isset($this->_pdf_objects[$oid]) ? $this->_pdf_objects[$oid] : false;
            }

        } else {
            // Prioritizing the new versions
            $object = isset($this->_pdf_objects[$oid]) ? $this->_pdf_objects[$oid] : false;
            if ($object === false)
                $object = PDFUtilFnc::find_object($this->_buffer, $this->_xref_table, $oid);
        }

        return $object;
    }

    /**
     * Function that sets the appearance of the signature (if the document is to be signed). At this time, it is possible to set
     *   the page in which the signature will appear, the rectangle, and an image that will be shown in the signature form.
     * @param page the page (zero based) in which the signature will appear
     * @param rect the rectangle (in page-based coordinates) where the signature will appear in that page
     * @param imagefilename an image file name (or an image in a buffer, with symbol '@' prepended) that will be put inside the rect
     */
    public function set_signature_appearance($page_to_appear = 0, $rect_to_appear = [0, 0, 0, 0], $imagefilename = null) {
        $this->_appearance = [
            "page" => $page_to_appear,
            "rect" => $rect_to_appear,
            "image" => $imagefilename
        ];
    }

    /**
     * Removes the settings of signature appearance (i.e. no signature will appear in the document)
     */
    public function clear_signature_appearance() {
        $this->_appearance = null;
    }

    /**
     * Removes the certificate for the signature (i.e. the document will not be signed)
     */
    public function clear_signature_certificate() {
        $this->_certificate = null;
    }
    
    /**
     * Function that stores the certificate to use, when signing the document
     * @param certfile a file that contains a user certificate
     * @param password the password to read the private key
     * @return valid true if the certificate can be used to sign the document, false otherwise
     */
    public function set_signature_certificate($certfile, $certpass = null) {
        $certificate = $certfile;

        if (isset($certfile['pcks12']) && $certfile['pcks12']) {
            // pkcs12 file
            openssl_pkcs12_read($certfile['pkey'], $certificate, $certpass);
        } else {
            // pem file
            // pkcs12 file
            $t_pkey = openssl_pkey_get_private($certificate['pkey'], $certpass);
            if ($t_pkey === false) {
                PhpdocxLogger::logger('Invalid private key', 'fatal');
            }

            openssl_pkey_export($t_pkey, $t_decpkey);
            $certificate['pkey'] = $t_decpkey;
        }

        // Store the certificate
        $this->_certificate = $certificate;

        return true;
    }

    /**
     * Function that creates and updates the PDF objects needed to sign the document. The workflow for a signature is:
     * - create a signature object
     * - create an annotation object whose value is the signature object
     * - create a form object (along with other objects) that will hold the appearance of the annotation object
     * - modify the root object to make acroform point to the annotation object
     * - modify the page object to make the annotations of that page include the annotation object
     * 
     * > If the appearance is not set, the image will not appear, and the signature object will be invisible.
     * > If the certificate is not set, the signature created will be a placeholder (that acrobat will able to sign)
     * 
     *      LIMITATIONS: one document can be signed once at a time; if wanted more signatures, then chain the documents:
     *      $o1->set_signature_certificate(...);
     *      $o2 = PDFDoc::fromstring($o1->to_pdf_file_s);
     *      $o2->set_signature_certificate(...);
     *      $o2->to_pdf_file_s();
     * 
     * @return signature a signature object, or null if the document is not signed; false if an error happens
     */
    protected function _generate_signature_in_document() {
        $imagefilename = null;
        $recttoappear = [ 0, 0, 0, 0];
        $pagetoappear = 0;

        if ($this->_appearance !== null) {
            $imagefilename = $this->_appearance["image"];
            $recttoappear = $this->_appearance["rect"];
            $pagetoappear = $this->_appearance["page"];
        }

        // First of all, we are searching for the root object (which should be in the trailer)
        $root = $this->_pdf_trailer_object["Root"];

        if (($root === false) || (($root = $root->get_object_referenced()) === false)) {
            PhpdocxLogger::logger('Could not find the root object from the trailer', 'warning');
        }

        $root_obj = $this->get_object($root);
        if ($root_obj === false) {
            PhpdocxLogger::logger('Invalid root object', 'warning');
        }

        // Now the object corresponding to the page number in which to appear
        $page_obj = $this->get_page($pagetoappear);
        if ($page_obj === false) {
            PhpdocxLogger::logger('Invalid page', 'warning');
        }
    
        // The objects to update
        $updated_objects = [ ];

        // Add the annotation to the page
        if (!isset($page_obj["Annots"])) {
            $page_obj["Annots"] = new PDFValueList();
        }

        $annots = &$page_obj["Annots"];

        if ((($referenced = $annots->get_object_referenced()) !== false) && (!is_array($referenced))) {
            // It is an indirect object, so we need to update that object
            $newannots = $this->create_object( 
                $this->get_object($referenced)->get_value()
            );
        } else {
            $newannots = $this->create_object(
                new PDFValueList()
            );
            $newannots->push($annots);
        }

        // Create the annotation object, annotate the offset and append the object
        $annotation_object = $this->create_object([
                "Type" => "/Annot",
                "Subtype" => "/Widget",
                "FT" => "/Sig",
                "V" => new PDFValueString(""),
                "T" => new PDFValueString('Signature' . get_random_string()),
                "P" => new PDFValueReference($page_obj->get_oid()),
                "Rect" => $recttoappear,
                "F" => 132  // TODO: check this value
            ]
        );      

        // Prepare the signature object (we need references to it)
        $signature = null;
        if ($this->_certificate !== null) {
            $signature = $this->create_object([], "PDFSignatureObject", false);
            // $signature = new PDFSignatureObject([]);
            $signature->set_certificate($this->_certificate);

            // Update the value to the annotation object
            $annotation_object["V"] = new PDFValueReference($signature->get_oid());
        }
        
        // If an image is provided, let's load it
        if ($imagefilename !== null) {
            // Signature with appearance, following the Adobe workflow: 
            //   1. form
            //   2. layers /n0 (empty) and /n2
            // https://www.adobe.com/content/dam/acom/en/devnet/acrobat/pdfs/acrobat_digital_signature_appearances_v9.pdf
    
            // Get the page height, to change the coordinates system (up to down)
            $pagesize = $this->get_page_size($pagetoappear);
            $pagesize = explode(" ", $pagesize[0]->val());
            $pagesize_h = floatval("" . $pagesize[3]) - floatval("" . $pagesize[1]);

            $bbox = [ 0, 0, $recttoappear[2] - $recttoappear[0], $recttoappear[3] - $recttoappear[1]];
            $form_object = $this->create_object([
                "BBox" => $bbox,
                "Subtype" => "/Form",
                "Type" => "/XObject",
                "Group" => [
                    'Type' => '/Group',
                    'S' => '/Transparency',
                    'CS' => '/DeviceRGB'
                ]
            ]);
    
            $container_form_object = $this->create_object([
                "BBox" => $bbox,
                "Subtype" => "/Form",
                "Type" => "/XObject",
                "Resources" => [ "XObject" => [
                    "n0" => new PDFValueSimple(""),
                    "n2" => new PDFValueSimple("")
                    ] ] 
                ]);
            $container_form_object->set_stream("q 1 0 0 1 0 0 cm /n0 Do Q\nq 1 0 0 1 0 0 cm /n2 Do Q\n", false);

            $layer_n0 = $this->create_object([
                "BBox" => [ 0.0, 0.0, 100.0, 100.0 ],
                "Subtype" => "/Form",
                "Type" => "/XObject",
                "Resources" => new PDFValueObject()
            ]);

            // Add the same structure than Acrobat Reader
            $layer_n0->set_stream("% DSBlank" . __EOL, false);

            $layer_n2 = $this->create_object([
                "BBox" => $bbox,
                "Subtype" => "/Form",
                "Type" => "/XObject",
                "Resources" => new PDFValueObject()
            ]);

            $result = _add_image([$this, "create_object"], $imagefilename, ...$bbox);
            if ($result === false) {
                PhpdocxLogger::logger('Could not add the image', 'warning');
            }

            $layer_n2["Resources"] = $result["resources"];
            $layer_n2->set_stream($result['command'], false);

            $container_form_object["Resources"]["XObject"]["n0"] = new PDFValueReference($layer_n0->get_oid());
            $container_form_object["Resources"]["XObject"]["n2"] = new PDFValueReference($layer_n2->get_oid());

            $form_object['Resources'] = new PDFValueObject([
                "XObject" => [
                    "FRM" => new PDFValueReference($container_form_object->get_oid())
                ]
            ]);
            $form_object->set_stream("/FRM Do", false);

            // Set the signature appearance field to the form object
            $annotation_object["AP"] = [ "N" => new PDFValueReference($form_object->get_oid())];
            $annotation_object["Rect"] = [ $recttoappear[0], $pagesize_h - $recttoappear[1], $recttoappear[2], $pagesize_h - $recttoappear[3] ];
        }

        if (!$newannots->push(new PDFValueReference($annotation_object->get_oid()))) {
            PhpdocxLogger::logger('Could not update the page where the signature has to appear', 'warning');
        }

        $page_obj["Annots"] = new PDFValueReference($newannots->get_oid());
        array_push($updated_objects, $page_obj);

        // AcroForm may be an indirect object
        if (!isset($root_obj["AcroForm"]))
            $root_obj["AcroForm"] = new PDFValueObject();

        $acroform = &$root_obj["AcroForm"];
        if ((($referenced = $acroform->get_object_referenced()) !== false) && (!is_array($referenced))) {
            $acroform = $this->get_object($referenced);
            array_push($updated_objects, $acroform);
        } else {
            array_push($updated_objects, $root_obj);
        }

        // Add the annotation to the interactive form
        $acroform["SigFlags"] = 3;
        if (!isset($acroform['Fields']))
            $acroform['Fields'] = new PDFValueList();

        // Add the annotation object to the interactive form
        if (!$acroform['Fields']->push(new PDFValueReference($annotation_object->get_oid()))) {
            PhpdocxLogger::logger('Could not create the signature field', 'warning');
        }

        // Store the objects
        foreach ($updated_objects as &$object) {
            $this->add_object($object);
        }     
        
        return $signature;
    }

    /**
     * Function that updates the modification date of the document. If modifies two parts: the "info" field of the trailer object
     *   and the xmp metadata field pointed by the root object.
     * @param date a DateTime object that contains the date to be set; null to set "now"
     * @return ok true if the date could be set; false otherwise
     */
    protected function update_mod_date($date = null) {
        // First of all, we are searching for the root object (which should be in the trailer)
        $root = $this->_pdf_trailer_object["Root"];

        if (($root === false) || (($root = $root->get_object_referenced()) === false)) {
            PhpdocxLogger::logger('Could not find the root object from the trailer', 'warning');
        }

        $root_obj = $this->get_object($root);
        if ($root_obj === false) {
            PhpdocxLogger::logger('Invalid root object', 'warning');
        }

        if ($date === null) {
            $date = new \DateTime();
        }

        // Update the xmp metadata if exists
        if (isset($root_obj["Metadata"])) {
            $metadata = $root_obj["Metadata"];
            if ((($referenced = $metadata->get_object_referenced()) !== false) && (!is_array($referenced))) {
                $metadata = $this->get_object($referenced);
                $metastream = $metadata->get_stream();
                $metastream = preg_replace('/<xmp:ModifyDate>([^<]*)<\/xmp:ModifyDate>/', '<xmp:ModifyDate>' . $date->format("c") . '</xmp:ModifyDate>', $metastream);
                $metastream = preg_replace('/<xmp:MetadataDate>([^<]*)<\/xmp:MetadataDate>/', '<xmp:MetadataDate>' . $date->format("c") . '</xmp:MetadataDate>', $metastream);
                $metastream = preg_replace('/<xmpMM:InstanceID>([^<]*)<\/xmpMM:InstanceID>/', '<xmpMM:InstanceID>uuid:' . UUID::v4() . '</xmpMM:InstanceID>', $metastream);
                $metadata->set_stream($metastream, false);
                $this->add_object($metadata);
            }
        }

        // Update the information object (not really needed)
        $info = $this->_pdf_trailer_object["Info"];
        if (($info === false) || (($info = $info->get_object_referenced()) === false)) {
            PhpdocxLogger::logger('Could not find the info object from the trailer', 'warning');
        }

        $info_obj = $this->get_object($info);
        if ($info_obj === false) {
            PhpdocxLogger::logger('Invalid info object', 'warning');
        }

        $info_obj["ModDate"] = new PDFValueString(timestamp_to_pdfdatestring($date));
        $this->add_object($info_obj);
        return true;
    }

    /**
     * Function that gets the objects that have been read from the document
     * @return objects an array of objects, indexed by the oid of each object
     */
    public function get_objects() {
        return $this->_pdf_objects;
    }

    /**
     * Function that gets the version of the document. It will have the form
     *   PDF-1.x
     * @return version the PDF version
     */
    public function get_version() {
        return $this->_pdf_version_string;
    }

    /**
     * Function that sets the version for the document. 
     * @param version the version of the PDF document (it shall have the form PDF-1.x)
     * @return correct true if the version had the proper form; false otherwise
     */
    public function set_version($version) {
        if (preg_match("/PDF-1.\[0-9\]/", $version) !== 1) {
            return false;
        }
        $this->_pdf_version_string = $version;
        return true;
    }

    /**
     * Function that creates a new PDFObject and stores it in the document object list, so that
     *   it is automatically managed by the document. The returned object can be modified and
     *   that modifications will be reflected in the document.
     * @param value the value that the object will contain
     * @return obj the PDFObject created
     */
    public function create_object($value = [], $class = "PDFObject", $autoadd = true) {
        if ($class == 'PDFObject') {
            $o = new PDFObject($this->get_new_oid(), $value);
        } else if ($class == 'PDFSignatureObject') {
            $o = new PDFSignatureObject($this->get_new_oid(), $value);
        }
        if ($autoadd === true) {
            $this->add_object($o);
        }
        return $o;
    }

    /**
     * Adds a pdf object to the document (overwrites the one with the same oid, if existed)
     * @param pdf_object the object to add to the document
     */
    public function add_object(PDFObject $pdf_object) {
        $oid = $pdf_object->get_oid();
        $this->_pdf_objects[$oid] = $pdf_object;

        // Update the maximum oid
        if ($oid > $this->_max_oid)
            $this->_max_oid = $oid;
    }

    /**
     * This function generates all the contents of the file up to the xref entry. 
     * @param rebuild whether to generate the xref with all the objects in the document (true) or
     *                consider only the new ones (false)
     * @return xref_data [ the text corresponding to the objects, array of offsets for each object ]
     */
    protected function _generate_content_to_xref($rebuild = false) {
        if ($rebuild === true) {
            $result  = new Buffer("%$this->_pdf_version_string" . __EOL);
        }  else {
            $result = new Buffer($this->_buffer);
        }

        // Need to calculate the objects offset
        $offsets = [];
        $offsets[0] = 0;

        // The objects
        $offset = $result->size();

        if ($rebuild === true) {
            for ($i = 0; $i <= $this->_max_oid; $i++) {
                if (($object = $this->get_object($i)) ===  false) continue;

                $result->data($object->to_pdf_entry());    
                $offsets[$i] = $offset;
                $offset = $result->size();
            }
        } else {
            foreach ($this->_pdf_objects as $obj_id => $object) {
                $result->data($object->to_pdf_entry());
                $offsets[$obj_id] = $offset;
                $offset = $result->size();
            }
        }

        return [ $result, $offsets ];
    }

    /**
     * This functions outputs the document to a buffer object, ready to be dumped to a file.
     * @param rebuild whether we are rebuilding the whole xref table or not (in case of incremental versions, we should use "false")
     * @return buffer a buffer that contains a pdf dumpable document
     */
    public function to_pdf_file_b($rebuild = false) {
        // We made no updates, so return the original doc
        if (($rebuild === false) && (count($this->_pdf_objects) === 0) && ($this->_certificate === null) && ($this->_appearance === null))
            return new Buffer($this->_buffer);

        // Save the state prior to generating the objects
        $this->push_state();

        // Update the timestamp
        $this->update_mod_date();
    
        $_signature = null;
        if (($this->_appearance !== null) || ($this->_certificate !== null)) {
            $_signature = $this->_generate_signature_in_document();
            if ($_signature === false) {
                $this->pop_state();
                PhpdocxLogger::logger('Could not generate the signed document', 'fatal');
            }
        }

        // Generate the first part of the document
        $valuesContentToXref = $this->_generate_content_to_xref($rebuild);
        $_doc_to_xref = $valuesContentToXref[0];
        $_obj_offsets = $valuesContentToXref[1];
        $xref_offset = $_doc_to_xref->size();

        if ($_signature !== null) {
            $_obj_offsets[$_signature->get_oid()] = $_doc_to_xref->size();
            $xref_offset +=  strlen($_signature->to_pdf_entry());
        }

        $doc_version_string = str_replace("PDF-", "", $this->_pdf_version_string);

        // The version considered for the cross reference table depends on the version of the current xref table,
        //   as it is not possible to mix xref tables. Anyway we are 
        $target_version = $this->_xref_table_version;
        if ($this->_xref_table_version >= "1.5") {
            // i.e. xref streams
            if ($doc_version_string > $target_version)
                $target_version = $doc_version_string;
        } else {
            // i.e. xref+trailer
            if ($doc_version_string < $target_version)
                $target_version = $doc_version_string;
        }

        if ($target_version >= "1.5") {
            // Create a new object for the trailer
            $trailer = $this->create_object(
                clone $this->_pdf_trailer_object
            );

            // Add this object to the offset table, to be also considered in the xref table
            $_obj_offsets[$trailer->get_oid()] = $xref_offset;

            // Generate the xref cross-reference stream
            $xref = PDFUtilFnc::build_xref_1_5($_obj_offsets);

            // Set the parameters for the trailer
            $trailer["Index"] = explode(" ", $xref["Index"]);
            $trailer["W"] = $xref["W"];
            $trailer["Size"] = $this->_max_oid + 1;
            $trailer["Type"] = "/XRef";

            // Not needed to generate new IDs, as in metadata the IDs will be set
            // $ID1 = md5("" . (new \DateTime())->getTimestamp() . "-" . $this->_xref_position . $xref["stream"]);
            $ID2 = md5("" . (new \DateTime())->getTimestamp() . "-" . $this->_xref_position . $this->_pdf_trailer_object);
            // $trailer["ID"] = [ new PDFValueHexString($ID1), new PDFValueHexString($ID2) ];
            $trailer["ID"] = [ $trailer["ID"][0], new PDFValueHexString(strtoupper($ID2)) ];

            // We are not using predictors nor encoding
            if (isset($trailer["DecodeParms"])) unset($trailer["DecodeParms"]);

            // We are not compressing the stream
            if (isset($trailer["Filter"])) unset($trailer["Filter"]);
            $trailer->set_stream($xref["stream"], false);

            // If creating an incremental modification, point to the previous xref table
            if ($rebuild === false)
                $trailer['Prev'] = $this->_xref_position;
            else
                // If rebuilding the document, remove the references to previous xref tables, because it will be only one
                if (isset($trailer['Prev']))
                    unset($trailer['Prev']);

            // And generate the part of the document related to the xref
            $_doc_from_xref = new Buffer($trailer->to_pdf_entry());
            $_doc_from_xref->data("startxref" . __EOL . "$xref_offset" . __EOL ."%%EOF" . __EOL);
        } else {
            $xref_content = PDFUtilFnc::build_xref($_obj_offsets);

            // Update the trailer
            $this->_pdf_trailer_object['Size'] = $this->_max_oid + 1;

            if ($rebuild === false)
                $this->_pdf_trailer_object['Prev'] = $this->_xref_position;

            // Not needed to generate new IDs, as in metadata the IDs may be set
            // $ID1 = md5("" . (new \DateTime())->getTimestamp() . "-" . $this->_xref_position . $xref_content);
            // $ID2 = md5("" . (new \DateTime())->getTimestamp() . "-" . $this->_xref_position . $this->_pdf_trailer_object);
            // $this->_pdf_trailer_object['ID'] = new PDFValueList(
            //    [ new PDFValueHexString($ID1), new PDFValueHexString($ID2) ]
            // );

            // Generate the part of the document related to the xref
            $_doc_from_xref = new Buffer($xref_content);
            $_doc_from_xref->data("trailer\n$this->_pdf_trailer_object");
            $_doc_from_xref->data("\nstartxref\n$xref_offset\n%%EOF\n");
        }

        if ($_signature !== null) {
            // In case that the document is signed, calculate the signature

            $_signature->set_sizes($_doc_to_xref->size(), $_doc_from_xref->size());
            $_signature["Contents"] = new PDFValueSimple("");
            $_signable_document = new Buffer($_doc_to_xref->get_raw() . $_signature->to_pdf_entry() . $_doc_from_xref->get_raw());

            // We need to write the content to a temporary folder to use the pkcs7 signature mechanism
            $temp_filename = tempnam(sys_get_temp_dir(), 'pdfsign');
            $temp_file = fopen($temp_filename, 'wb');
            fwrite($temp_file, $_signable_document->get_raw());
            fclose($temp_file);

            // Calculate the signature and remove the temporary file
            $certificate = $_signature->get_certificate();
            $signature_contents = PDFUtilFnc::calculate_pkcs7_signature($temp_filename, $certificate['cert'], $certificate['pkey'], sys_get_temp_dir());
            unlink($temp_filename);

            // Then restore the contents field
            $_signature["Contents"] = new PDFValueHexString($signature_contents);

            // Add this object to the content previous to this document xref
            $_doc_to_xref->data($_signature->to_pdf_entry());
        }

        // Reset the state to make signature objects not to mess with the user's objects
        $this->pop_state();
        return new Buffer($_doc_to_xref->get_raw() . $_doc_from_xref->get_raw());
    }

    /**
     * This functions outputs the document to a string, ready to be written
     * @return buffer a buffer that contains a pdf document
     */
    public function to_pdf_file_s($rebuild = false) {
        $pdf_content = $this->to_pdf_file_b($rebuild);
        return $pdf_content->get_raw();
    }

    /**
     * This function writes the document to a file
     * @param filename the name of the file to be written (it will be overwritten, if exists)
     * @return written true if the file has been correcly written to the file; false otherwise
     */
    public function to_pdf_file($filename, $rebuild = false) {
        $pdf_content = $this->to_pdf_file_b($rebuild);

        $file = fopen($filename, "wb");
        if ($file === false) {
            PhpdocxLogger::logger('Failed to create the file', 'fatal');
        }
        if (fwrite($file, $pdf_content->get_raw()) !== $pdf_content->size()) {
            fclose($file);
            PhpdocxLogger::logger('Failed to create the file', 'fatal');
        }
        fclose($file);
        return true;
    }

    /**
     * Gets the page object which is rendered in position i
     * @param i the number of page (according to the rendering order)
     * @return page the page object
     */
    public function get_page($i) {
        if ($i < 0) return false;
        if ($i >= count($this->_pages_info)) return false;
        return $this->get_object($this->_pages_info[$i]['id']);
    }

    /**
     * Gets the size of the page in the form of a rectangle [ x0 y0 x1 y1 ]
     * @param i the number of page (according to the rendering order), or the page object
     * @return box the bounding box of the page
     */
    public function get_page_size($i) {
        $pageinfo = false;
        
        if (is_int($i)) {
            if ($i < 0) return false;
            if ($i > count($this->_pages_info)) return false;

            $pageinfo = $this->_pages_info[$i]['info'];
        } else {
            foreach ($this->_pages_info as $k => $info) {
                if ($info['oid'] === $i->get_oid()) {
                    $pageinfo = $info['info'];
                    break;
                }
            }
        }

        // The page has not been found
        if (($pageinfo === false) || (!isset($pageinfo['size'])))
            return false;

        return $pageinfo['size'];
    }

    /**
     * This function builds the page IDs for object with id oid. If it is a page, it returns the oid; if it is not and it has 
     *   kids and every kid is a page (or a set of pages), it finds the pages.
     * @param oid the object id to inspect
     * @return pages the ordered list of page ids corresponding to object oid, or false if any of the kid objects
     *               is not of type page or pages.
     */
    protected function _get_page_info($oid, $info = []) {
        $object = $this->get_object($oid);
        if ($object === false) {
            PhpdocxLogger::logger('Could not get information about the page', 'warning');
        }

        $page_ids = [];

        switch ($object["Type"]->val()) {
            case "Pages":
                $kids = $object["Kids"];
                $kids = $kids->get_object_referenced();
                if ($kids !== false) {
                    if (isset($object['MediaBox'])) {
                        $info['size'] = $object['MediaBox']->val();
                    }
                    foreach ($kids as $kid) {
                        $ids = $this->_get_page_info($kid, $info);
                        if ($ids === false)
                            return false;
                        array_push($page_ids, ...$ids);
                    }
                } else {
                    PhpdocxLogger::logger('Could not get the pages', 'warning');
                }
                break;
            case "Page":
                if (isset($object['MediaBox']))
                    $info['size'] = $object['MediaBox']->val();
                return [ [ 'id' => $oid, 'info' => $info ]  ];
            default:
                return false;
        }
        return $page_ids;
    }

    /**
     * Obtains an ordered list of objects that contain the ids of the page objects of the document.
     *   The order is made according to the catalog and the document structure.
     * @return list an ordered list of the id of the page objects, or false if could not be found
     */
    protected function _acquire_pages_info() {
        $root = $this->_pdf_trailer_object["Root"];
        if (($root === false) || !$root || (($root = $root->get_object_referenced()) === false)) {
            PhpdocxLogger::logger('Could not find the root object from the trailer', 'warning');
        }

        $root = $this->get_object($root);
        $pages = $root["Pages"];
        if ($root !== false) {
            if (($pages === false) || !$pages || (($pages = $pages->get_object_referenced()) === false)) {
                PhpdocxLogger::logger('Could not find the pages for the document', 'warning');
            }
            
            $this->_pages_info = $this->_get_page_info($pages);
        }
    }    
}

class PDFDocWithContents extends PDFDoc {

    const T_STANDARD_FONTS = [
        "Times-Roman", 
        "Times-Bold", 
        "Time-Italic", 
        "Time-BoldItalic", 
        "Courier", 
        "Courier-Bold", 
        "Courier-Oblique", 
        "Courier-BoldOblique",
        "Helvetica", 
        "Helvetica-Bold", 
        "Helvetica-Oblique", 
        "Helvetica-BoldOblique", 
        "Symbol", 
        "ZapfDingbats"
    ];

    /**
     * This is a function that allows to add a very basic text to a page, using a standard font.
     *   The function is mainly oriented to add banners and so on, and not to use for writting.
     * @param page the number of page in which the text should appear
     * @param text the text
     * @param x the x offset from left for the text (we do not take care of margins)
     * @param y the y offset from top for the text (we do not take care of margins)
     * @param params an array of values [ "font" => <fontname>, "size" => <size in pt>, 
     *               "color" => <#hexcolor>, "angle" => <rotation angle>]
     */
    public function add_text($page_to_appear, $text, $x, $y, $params = []) {
        // TODO: maybe we can create a function that "adds content to a page", and that
        //       function will search for the content field and merge the resources, if
        //       needed

        $default = [
            "font" => "Helvetica",
            "size" => 24,
            "color" => "#000000",
            "angle" => 0
        ];

        $params = array_merge($default, $params);

        $page_obj = $this->get_page($page_to_appear);
        if ($page_obj === false) {
            PhpdocxLogger::logger('Invalid page', 'warning');
        }

        $resources_obj = $this->get_indirect_object($page_obj['Resources']);

        if (array_search($params["font"], self::T_STANDARD_FONTS) === false) {
            PhpdocxLogger::logger('Only standard fonts are allowed Times-Roman, Helvetica, Courier, Symbol, ZapfDingbats', 'warning');
        }

        $font_id = "F" . get_random_string(4);
        $resources_obj['Font'][$font_id] = [
            "Type" => "/Font",
            "Subtype" => "/Type1",
            "BaseFont" => "/" . $params['font'],
        ];

        // Get the contents for the page
        $contents_obj = $this->get_indirect_object($page_obj['Contents']);

        $data = $contents_obj->get_stream(false);
        if ($data === false) {
            PhpdocxLogger::logger('Could not interpret the contents of the page', 'warning');
        }

        // Get the page height, to change the coordinates system (up to down)
        $pagesize = $this->get_page_size($page_to_appear);
        $pagesize_h = floatval("" . $pagesize[3]) - floatval("" . $pagesize[1]);

        $angle = $params["angle"];
        $angle *= M_PI/180;
        $c = cos($angle);
        $s = sin($angle);
        $cx = $x;
        $cy = ($pagesize_h - $y);

        if ($angle !== 0)
            $rotate_command = sprintf("%.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm", $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy);

        $text_command = "BT ";
        $text_command .= "/$font_id "  . $params['size'] . " Tf ";
        $text_command .= sprintf("%.2f %.2f Td ", $x, $pagesize_h - $y); // Ubicar en x, y
        $text_command .= sprintf("(%s) Tj ", $text);
        $text_command .= "ET ";

        $color = $params["color"];
        if ($color[0] === '#') {
            $colorvalid = true;
            $r = null;
            switch (strlen($color)) {
                case 4:
                    $color = "#" . $color[1] . $color[1] . $color[2] . $color[2] . $color[3] . $color[3];
                case 7:
                    list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");                    
                    break;
            }
            if ($r !== null)
                $text_command = " q $r $g $b rg $text_command Q"; // Color RGB
        }

        if ($angle !== 0) {
            $text_command = " q $rotate_command $text_command Q";    
        }
            
        $data .= $text_command;

        $contents_obj->set_stream($data, false);

        // Update the contents
        $this->add_object($resources_obj);
        $this->add_object($contents_obj);        
    }

    /**
     * Adds an image to the document, in the specific page
     *   NOTE: the image inclusion is taken from http://www.fpdf.org/; this is an adaptation
     *         and simplification of function Image(); it does not take care about units nor 
     *         page breaks
     * @param page_obj the page object (or the page number) in which the image will appear
     * @param filename the name of the file that contains the image (or the content of the file, with the character '@' prepended)
     * @param x the x position (in pixels) where the image will appear
     * @param y the y position (in pixels) where the image will appear
     * @param w the width of the image
     * @param w the height of the image
     */
    public function add_image($page_obj, $filename, $x=0, $y=0, $w=0, $h=0) {

        // TODO: maybe we can create a function that "adds content to a page", and that
        //       function will search for the content field and merge the resources, if
        //       needed

        // Check that the page is valid
        if (is_int($page_obj))
            $page_obj = $this->get_page($page_obj);

        if ($page_obj === false) {
            PhpdocxLogger::logger('Invalid page', 'warning');
        }
            
        // Get the page height, to change the coordinates system (up to down)
        $pagesize = $this->get_page_size($page_obj);
        $pagesize_h = floatval("" . $pagesize[3]) - floatval("" . $pagesize[1]);

        $result = $this->_add_image($filename, $x, $pagesize_h - $y, $w, $h);

        // Get the resources for the page
        $resources_obj = $this->get_indirect_object($page_obj['Resources']);
        if (!isset($resources_obj['ProcSet'])) {
            $resources_obj['ProcSet'] = new PDFValueList(['/PDF']);
        }
        $resources_obj['ProcSet']->push(['/ImageB', '/ImageC', '/ImageI']);
        if (!isset($resources_obj['XObject'])) {
            $resources_obj['XObject'] = new PDFValueObject();
        }
        $resources_obj['XObject'][$info['i']] = new PDFValueReference($images_objects[0]->get_oid());

        // TODO: get the contents object in which to add the image.
        //       this is a bit hard, because we have multiple options (e.g. the contents is an indirect object
        //       or the contents is an array of objects)
        $contents_obj = $this->get_indirect_object($page_obj['Contents']);

        $data = $contents_obj->get_stream(false);
        if ($data === false) {
            PhpdocxLogger::logger('Could not interpret the contents of the page', 'warning');
        }

        // Append the command to draw the image
        $data .= $result['command'];

        // Update the contents of the page
        $contents_obj->set_stream($data, false);

        if ($add_alpha === true) {
            $page_obj['Group'] = new PDFValueObject([
                'Type' => '/Group',
                'S' => '/Transparency',
                'CS' => '/DeviceRGB'
            ]);
            $this->add_object($page_obj);
        }

        foreach ([$resources_obj, $contents_obj] as $o )
            $this->add_object($o);

        return true;
    }
}

// The character used to end lines
if (!defined('__EOL'))
    define('__EOL', "\n");

/**
 * Class to gather the information of a PDF object: the OID, the definition and the stream. The purpose is to 
 *   ease the generation of the PDF entries for an individual object.
 */
class PDFObject implements \ArrayAccess {
    protected $_oid = null;
    protected $_stream = null;
    protected $_value = null;
    
    public function __construct($oid, $value = null, $generation = 0) {
        if ($generation !== 0) {
            PhpdocxLogger::logger('Non-zero generation objects are not supported', 'fatal');
        }

        // If the value is null, we suppose that we are creating an empty object
        if ($value === null)
            $value = new PDFValueObject();

        // Ease the creation of the object
        if (is_array($value)) {
            $obj = new PDFValueObject();
            foreach ($value as $field => $v) {
                $obj[$field] = $v;
            }
            $value = $obj;
        }

        $this->_oid = $oid;
        $this->_value = $value;
    }

    public function get_keys() {
        return $this->_value->get_keys();
    }

    public function set_oid($oid) {
        $this->_oid = $oid;
    }

    public function __toString() {
        return  "$this->_oid 0 obj\n" .
            "$this->_value\n" .
            ($this->_stream === null?"":
                "stream\n" .
                '...' . 
                "\nendstream\n"
            ) .
            "endobj\n";
    }
    /**
     * Converts the object to a well-formed PDF entry with a form like
     *  1 0 obj
     *  ...
     *  stream
     *  ...
     *  endstream
     *  endobj
     * @return pdfentry a string that contains the PDF entry
     */
    public function to_pdf_entry() {
        return  "$this->_oid 0 obj" . __EOL .
                "$this->_value" . __EOL .
                ($this->_stream === null?"":
                    "stream\r\n" .
                    $this->_stream . 
                    __EOL . "endstream" . __EOL
                ) .
                "endobj" . __EOL;
    }
    /**
     * Gets the object ID
     * @return oid the object id
     */
    public function get_oid() {
        return $this->_oid;
    }
    /**
     * Gets the definition of the object (a PDFValue object)
     * @return value the definition of the object
     */
    public function get_value() {
        return $this->_value;
    }
    protected static function FlateDecode($_stream, $params) {
        switch ($params["Predictor"]->get_int()) {
            case 1:
                    return $_stream;
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
                    break;
        }

        switch($params["Colors"]->get_int()) {
            case 1:
                break;
        }

        switch($params["BitsPerComponent"]->get_int()) {
            case 8:
                break;
        }

        $decoded = new Buffer();
        $columns = $params['Columns']->get_int();

        $row_len = $columns + 1;
        $stream_len = strlen($_stream);

        // The previous row is zero
        $data_prev = str_pad("", $columns, chr(0));
        $row_i = 0;
        $pos_i = 0;
        $data = str_pad("", $columns, chr(0));
        while ($pos_i < $stream_len) {
            $filter_byte = ord($_stream[$pos_i++]);

            // Get the current row
            $data = substr($_stream, $pos_i, $columns);
            $pos_i += strlen($data);

            // Zero pad, in case that the content is not paired
            $data = str_pad($data, $columns, chr(0));

            // Depending on the filter byte of the row, we should unpack on one way or another
            switch ($filter_byte) {
                case 0: 
                    break;
                case 1: 
                    for ($i = 1; $i < $columns; $i++)
                        $data[$i] = ($data[$i] + $data[$i-1]) % 256;
                    break;
                case 2: 
                    for ($i = 0; $i < $columns; $i++) {
                        $data[$i] = chr((ord($data[$i]) + ord($data_prev[$i])) % 256);
                    }
                    break;
                default: 
                    PhpdocxLogger::logger('Unsupported stream', 'warning');
            }

            // Store and prepare the previous row
            $decoded->data($data);
            $data_prev = $data;
        }

        return $decoded->get_raw();
    }

    /**
     * Gets the stream of the object
     * @return stream a string that contains the stream of the object
     */
    public function get_stream($raw = true) {
        if ($raw === true)
            return $this->_stream;
        if (isset($this->_value['Filter'])) {
            switch ($this->_value['Filter']) {
                case '/FlateDecode':
                    $DecodeParams = isset($this->_value['DecodeParms']) ? $this->_value['DecodeParms'] : [];
                    $params = [
                        "Columns" => isset($DecodeParams['Columns']) ? $DecodeParams['Columns'] : new PDFValueSimple(0),
                        "Predictor" => isset($DecodeParams['Predictor']) ? $DecodeParams['Predictor'] : new PDFValueSimple(1),
                        "BitsPerComponent" => isset($DecodeParams['BitsPerComponent']) ? $DecodeParams['BitsPerComponent'] : new PDFValueSimple(8),
                        "Colors" => isset($DecodeParams['Colors']) ? $DecodeParams['Colors'] : new PDFValueSimple(1),
                    ];
                    return self::FlateDecode(gzuncompress($this->_stream), $params);
                    
                    break;
                default:
                    PhpdocxLogger::logger('Unknown compression method ' . $this->_value['Filter'], 'warning');
            }
        }
        return $this->_stream;
    }
    /**
     * Sets the stream for the object (overwrites a previous existing stream)
     * @param stream the stream for the object
     */
    public function set_stream($stream, $raw = true) {
        if ($raw === true) {
            $this->_stream = $stream;
            return;
        }
        if (isset($this->_value['Filter'])) {
            switch ($this->_value['Filter']) {
                case '/FlateDecode':
                    $stream = gzcompress($stream);
                    break;
                default:
                    PhpdocxLogger::logger('Unknown compression method ' . $this->_value['Filter'], 'warning');
            }
        }
        $this->_value['Length'] = strlen($stream);
        $this->_stream = $stream;
    }    
    /**
     * The next functions enble to make use of this object in an array-like manner,
     *  using the name of the fields as positions in the array. It is useful is the
     *  value is of type PDFValueObject or PDFValueList, using indexes
     */

    /** 
     * Sets the value of the field offset, using notation $obj['field'] = $value
     * @param field the field to set the value
     * @param value the value to set
     * @return value the value, to chain operations
     */
    public function offsetSet($field, $value) {
        return $this->_value[$field] = $value;
    }
    /**
     * Checks whether the field exists in the object or not (or if the index exists
     *   in the list)
     * @param field the field to check wether exists or not
     * @return exists true if the field exists; false otherwise
     */
    public function offsetExists ( $field ) {
        return $this->_value->offsetExists($field);
    }
    /**
     * Gets the value of the field (or the value at position)
     * @param field the field to get the value
     * @return value the value of the field
     */
    public function offsetGet ( $field ) {
        return $this->_value[$field];
    }
    /**
     * Unsets the value of the field (or the value at position)
     * @param field the field to unset the value
     */
    public function offsetUnset($field ) {
        $this->_value->offsetUnset($field);
    }    

    public function push($v) {
        return $this->_value->push($v);
    }
}

/**
 * Class devoted to parse a single PDF object
 * 
 * A PDF Document is made of objects with the following structure (e.g for object 1 version 0)
 * 
 * 1 0 obj
 * ...content...
 * [stream
 * ...stream...
 * endstream]
 * endobject
 * 
 * This PDF class transforms the definition string within ...content... into a PDFValue class.
 * 
 * - At the end, it is a simple syntax checker
 */
class PDFObjectParser {

    // Possible tokens in a PDF document
    const T_NOTOKEN = 0;
    const T_LIST_START = 1;
    const T_LIST_END = 2;
    const T_FIELD = 3;
    const T_STRING = 4;
    const T_HEX_STRING = 12;
    const T_SIMPLE = 5;
    const T_DICT_START = 6;
    const T_DICT_END = 7;
    const T_OBJECT_BEGIN = 8;
    const T_OBJECT_END = 9;
    const T_STREAM_BEGIN = 10;
    const T_STREAM_END = 11;
    const T_COMMENT = 13;

    const T_NAMES = [
        self::T_NOTOKEN => 'no token',
        self::T_LIST_START => 'list start',
        self::T_LIST_END => 'list end',
        self::T_FIELD => 'field',
        self::T_STRING => 'string',
        self::T_HEX_STRING => 'hex string',
        self::T_SIMPLE => 'simple',
        self::T_DICT_START => 'dict start',
        self::T_DICT_END => 'dict end',
        self::T_OBJECT_BEGIN => 'object begin',
        self::T_OBJECT_END => 'object end',
        self::T_STREAM_BEGIN => 'stream begin',
        self::T_STREAM_END => 'stream end',
        self::T_COMMENT => 'comment'
    ];

    const T_SIMPLE_OBJECTS = [
        self::T_SIMPLE,
        self::T_OBJECT_BEGIN,
        self::T_OBJECT_END,
        self::T_STREAM_BEGIN,
        self::T_STREAM_END,
        self::T_COMMENT
    ];

    protected $_buffer = null;
    protected $_c = false;
    protected $_n = false;
    protected $_t = false;
    protected $_tt = self::T_NOTOKEN;

    /**
     * Retrieves the current token type (one of T_* constants)
     * @return token the current token
     */
    public function current_token() {
        return $this->_tt;
    }

    /**
     * Obtains the next char and prepares the variable $this->_c and $this->_n to contain the current char and the next char
     *  - if EOF, _c will be false
     *  - if the last char before EOF, _n will be false
     * @return char the next char
     */
    protected function nextchar() {
        $this->_c = $this->_n;
        $this->_n = $this->_buffer->nextchar();
        return $this->_c;
    }

    /**
     * Prepares the parser to analythe the text (i.e. prepares the parsing variables)
     */
    protected function start(&$buffer) {
        $this->_buffer = $buffer;
        $this->_c = false;
        $this->_n = false;
        $this->_t = false;
        $this->_tt = self::T_NOTOKEN;

        if ($this->_buffer->size() === 0) return false;
        $this->_n = $this->_buffer->currentchar();
        $this->nextchar();
    }

    /**
     * Parses the document
     */
    public function parse(&$stream) { // $str, $offset = 0) {
        $this->start($stream); //$str, $offset);
        $this->nexttoken();
        $result = $this->_parse_value();
        return $result;
    }

    public function parsestr($str, $offset = 0) {
        $stream = new StreamReader($str);
        $stream->gotopos($offset);
        return $this->parse($stream);
    }

    /**
     * Simple output of the object
     * @return output the output of the object
     */
    public function __toString() {
        return "pos: " . $this->_buffer->getpos() . ", c: $this->_c, n: $this->_n, t: $this->_t, tt: " .
        self::T_NAMES[$this->_tt] . ', b: ' . $this->_buffer->substratpos(50) .
        "\n";
    }

    /**
     * Obtains the next token and returns it
     */
    public function nexttoken() {
        $valuesToken = $this->token();
        $this->_t = $valuesToken[0];
        $this->_tt = $valuesToken[1];
        return $this->_t;
    }

    /**
     * Function that returns wether the current char is a separator or not
     */
    protected function _c_is_separator() {
        $DSEPS =[ "<<", ">>" ];

        return (($this->_c === false) || (strpos("%<>()[]{}/ \n\r\t", $this->_c) !== false) || ((array_search($this->_c . $this->_n, $DSEPS)) !== false));
    }

    /**
     * Analyzes the buffer from the current char and gets the next token (text and type)
     * @return [token, token type] the token string and the token type
     * This function assumes that the next content is an hex string, so it should be called after "<" is detected; it skips the trailing ">"
     * @return string the hex string
     */
    protected function _parse_hex_string() {
        $token = "";

        if ($this->_c !== "<") {
            PhpdocxLogger::logger('Invalid hex string', 'warning');
        }

        $this->nextchar();  // This char is "<"
        while (($this->_c !== '>') && (strpos("0123456789abcdefABCDEF \t\r\n\f", $this->_c) !== false)) {
            $token .= $this->_c;
            if ($this->nextchar() === false) {
                break;
            }
        }
        if (($this->_c !== false) && (strpos(">0123456789abcdefABCDEF \t\r\n\f", $this->_c) === false)) {
            PhpdocxLogger::logger('Invalid hex string', 'warning');
        }

        // The only way to get to here is that char is ">"
        if ($this->_c !== ">") {
            PhpdocxLogger::logger('Invalid hex string', 'warning');
        }

        $this->nextchar();
        
        return $token;
    }

    protected function _parse_string() {
        $token = "";
        if ($this->_c !== "(") {
            PhpdocxLogger::logger('Invalid string', 'warning');
        }

        $n_parenthesis = 1;
        while ($this->_c !== false) {
            $this->nextchar();
            if ($this->_c === ')') {
                $n_parenthesis--;
                if ($n_parenthesis == 0)
                    break;
            } else {
                if ($this->_c === '(') {
                    $n_parenthesis++;
                }
                $token .= $this->_c;
            }
        }

        if ($this->_c !== ")") {
            PhpdocxLogger::logger('Invalid string', 'warning');
        }
        $this->nextchar();

        return $token;
    }

    /**
     * Analyzes the buffer from the current char and gets the next token (text and type)
     * @return [token, token type] the token string and the token type
     */
    protected function token() {
        if ($this->_c === false) return [ false, false ];

        // The resulting token
        $token = false;

        while ($this->_c !== false) {
            // Skip the spaces
            while ((strpos("\t\n\r ", $this->_c) !== false) && ($this->nextchar() !== false)) ;

            $token_type = self::T_NOTOKEN;

            // TODO: hexadecimal strings are not parsed properly, according to section 7.3.4; the hexadecimal string are <abcdef12345>; where all the shall be hexadecimal values or separators
            // TODO: literal strings are not parsed properly, according to section 7.3.4.2: the strings may contain "balanced pairs of parentheses" and may "require no special treatment"; i.e. (this is a (correct) string)
            // TODO: also the special characters are not "strictly" considered, according to section 7.3.4.2: \n \r \t \b \f \( \) \\ are valid; the other not; but also \bbb should be considered; all of them are "sufficiently" treated, but other unknown caracters such as \u are also accepted
            switch ($this->_c) {
                case '%':
                    $this->nextchar();
                    $token = "";
                    while (strpos("\n\r", $this->_c) === false) {
                        $token .= $this->_c;
                        $this->nextchar();
                    }
                    $token_type = self::T_COMMENT;
                break;
                case '<':
                    if ($this->_n === '<') {
                        $this->nextchar();
                        $this->nextchar();
                        $token = '<<';
                        $token_type = self::T_DICT_START;
                    } else {
                        $token = $this->_parse_hex_string();
                        $token_type = self::T_HEX_STRING;
                    }
                    break;
                case '(':
                        $token = $this->_parse_string();
                        $token_type = self::T_STRING;
                        break;
                case '>':
                    if ($this->_n === '>') {
                        $this->nextchar();
                        $this->nextchar();
                        $token = '>>';
                        $token_type = self::T_DICT_END;
                    }
                    break;
                case '[':
                    $token = $this->_c;
                    $this->nextchar();
                    $token_type = self::T_LIST_START;
                    break;
                case ']':
                    $token = $this->_c;
                    $this->nextchar();
                    $token_type = self::T_LIST_END;
                    break;
                case '/':
                    // Skip the field idenifyer
                    $this->nextchar();

                    // We are assuming any char (i.e. /MY+difficult_id is valid)
                    while (!$this->_c_is_separator()) {
                        $token .= $this->_c;
                        if ($this->nextchar() === false) break;
                    }
                    $token_type = self::T_FIELD;
                    break;
            }

            if ($token === false) {
                $token = "";

                while (!$this->_c_is_separator()) {
                    $token .= $this->_c;
                    if ($this->nextchar() === false) break;
                }

                switch ($token) {
                    case 'obj':
                        $token_type = self::T_OBJECT_BEGIN; break;
                    case 'endobj':
                        $token_type = self::T_OBJECT_END; break;
                    case 'stream':
                        $token_type = self::T_STREAM_BEGIN; break;
                    case 'endstream':
                        $token_type = self::T_STREAM_END; break;
                    default:
                        $token_type = self::T_SIMPLE; break;
                }
                
            }
            return [ $token, $token_type ];
        }
    }

    protected function _parse_obj() {
        if ($this->_tt !== self::T_DICT_START) {
            PhpdocxLogger::logger('Invalid object defintion', 'fatal');
        }

        $this->nexttoken();
        $object = [];
        while ($this->_t !== false) {
            switch ($this->_tt) {
                case self::T_FIELD:
                    $field = $this->_t;
                    $this->nexttoken();
                    $object[$field] = $this->_parse_value();
                    break;
                case self::T_DICT_END:
                    $this->nexttoken();
                    return new PDFValueObject($object);
                    break;
                default:
                    PhpdocxLogger::logger('Invalid token: ' . $this, 'fatal');
            }
        }
        return false;
    }

    protected function _parse_list() {
        if ($this->_tt !== self::T_LIST_START) {
            PhpdocxLogger::logger('Invalid list definition', 'fatal');
        }

        $this->nexttoken();
        $list = [];
        while ($this->_t !== false) {
            switch ($this->_tt) {
                case self::T_LIST_END:
                    $this->nexttoken();
                    return new PDFValueList($list);

                case self::T_OBJECT_BEGIN:
                case self::T_OBJECT_END:
                case self::T_STREAM_BEGIN:
                case self::T_STREAM_END:
                    PhpdocxLogger::logger('Invalid list definition', 'fatal');
                    break;
                default:
                    $value = $this->_parse_value();
                    if ($value !== false) {
                        array_push($list, $value);
                    }
                    break;
            }
        }
        return new PDFValueList($list);
    }

    protected function _parse_value() {
        while ($this->_t !== false) {
            switch ($this->_tt) {
                case self::T_DICT_START:
                    return $this->_parse_obj();
                    break;
                case self::T_LIST_START:
                    return $this->_parse_list();
                    break;
                case self::T_STRING:
                    $string = new PDFValueString($this->_t);
                    $this->nexttoken();
                    return $string;
                    break;
                case self::T_HEX_STRING:
                    $string = new PDFValueHexString($this->_t);
                    $this->nexttoken();
                    return $string;
                    break;
                case self::T_FIELD:
                    $field = new PDFValueType($this->_t);
                    $this->nexttoken();
                    return $field;
                case self::T_OBJECT_BEGIN:
                case self::T_STREAM_END:
                    PhpdocxLogger::logger('Invalid list definition', 'fatal');
                case self::T_OBJECT_END:
                case self::T_STREAM_BEGIN:
                    return null;
                case self::T_COMMENT:
                    $this->nexttoken();
                    break;
                case self::T_SIMPLE:
                    $simple_value = $this->_t;
                    $this->nexttoken();
                    while (($this->_t !== false) && ($this->_tt == self::T_SIMPLE))  {
                        $simple_value .= " " . $this->_t;
                        $this->nexttoken();
                    }
                    return new PDFValueSimple($simple_value);
                    break;

                default:
                    PhpdocxLogger::logger('Invalid token: ' . $this, 'fatal');
            }
        }
        return false;
    }

    function tokenize() {
        $this->start();
        while ($this->nexttoken() !== false) {
            echo "$this->_t\n";
        }
    }
}

// The maximum signature length, needed to create a placeholder to calculate the range of bytes
// that will cover the signature.
if (!defined('__SIGNATURE_MAX_LENGTH')) {
    define('__SIGNATURE_MAX_LENGTH', 11742);
}

// The maximum expected length of the byte range, used to create a placeholder while the size
// is not known. 68 digits enable 20 digits for the size of the document
if (!defined('__BYTERANGE_SIZE')) {
    define('__BYTERANGE_SIZE', 68);
}

// This is an special object that has a set of fields
class PDFSignatureObject extends PDFObject {
    // A placeholder for the certificate to use to sign the document
    protected $_certificate = null;
    /**
     * Sets the certificate to use to sign
     * @param cert the pem-formatted certificate and private to use to sign as 
     *             [ 'cert' => ..., 'pkey' => ... ]
     */
    public function set_certificate($certificate) {
        $this->_certificate = $certificate;
    }
    /**
     * Obtains the certificate set with function set_certificate
     * @return cert the certificate
     */
    public function get_certificate() {
        return $this->_certificate;
    }
    /**
     * Constructs the object and sets the default values needed to sign
     * @param oid the oid for the object
     */
    public function __construct($oid) {
        $this->_prev_content_size = 0;
        $this->_post_content_size = null;
        parent::__construct($oid, [
            'Filter' => "/Adobe.PPKLite",
            'Type' => "/Sig",
            'SubFilter' => "/adbe.pkcs7.detached",
            'ByteRange' => new PDFValueSimple(str_repeat(" ", __BYTERANGE_SIZE)),
            'Contents' => "<" . str_repeat("0", __SIGNATURE_MAX_LENGTH) . ">",
            'M' => new PDFValueString(timestamp_to_pdfdatestring()),
        ]);
    }
    /**
     * Function used to add some metadata fields to the signature: name, reason of signature, etc.
     * @param name the name of the signer
     * @param reason the reason for the signature
     * @param location the location of signature
     * @param contact the contact info
     */
    public function set_metadata($name = null, $reason = null, $location = null, $contact = null) {
        $this->_value["Name"] = $name;
        $this->_value["Reason"] = $reason;
        $this->_value["Location"] = $location;
        $this->_value["ContactInfo"] = $contact;
    }
    /**
     * Function that sets the size of the content that will appear in the file, previous to this object,
     *   and the content that will be included after. This is needed to get the range of bytes of the
     *   signature.
     */
    public function set_sizes($prev_content_size, $post_content_size = null) {
        $this->_prev_content_size = $prev_content_size;
        $this->_post_content_size = $post_content_size;
    }
    /**
     * This function gets the offset of the marker, relative to this object. To make correct, the offset of the object
     *   shall have properly been set. It makes use of the parent "to_pdf_entry" function to avoid recursivity.
     * @return position the position of the <0000 marker
     */
    public function get_signature_marker_offset() {
        $tmp_output = parent::to_pdf_entry();
        $marker = "/Contents";
        $position = strpos($tmp_output, $marker);
        return $position + strlen($marker);
    }
    /**
     * Overrides the parent function to calculate the proper range of bytes, according to the sizes provided and the
     *   string representation of this object
     * @return str the string representation of this object
     */
    public function to_pdf_entry() {
        $signature_size = strlen(parent::to_pdf_entry());
        $offset = $this->get_signature_marker_offset();
        $starting_second_part = $this->_prev_content_size + $offset + __SIGNATURE_MAX_LENGTH + 2;

        $contents_size = strlen("" . $this->_value['Contents']);

        $byterange_str =  "[ 0 " . 
            ($this->_prev_content_size + $offset) . " " .
            ($starting_second_part) . " " .
            ($this->_post_content_size!==null?$this->_post_content_size + ($signature_size - $contents_size - $offset):0) . " ]";

        $this->_value['ByteRange'] = 
            new PDFValueSimple($byterange_str . str_repeat(" ", __BYTERANGE_SIZE - strlen($byterange_str) + 1)
        );

        return parent::to_pdf_entry();
    }
}

// TODO: use the streamreader to deal with the document in the file, instead of a buffer

class PDFUtilFnc {

    public static function get_trailer(&$_buffer, $trailer_pos) {
        // Search for the trailer structure
        if (preg_match('/trailer\s*(.*)\s*startxref/ms', $_buffer, $matches, 0, $trailer_pos) !== 1) {
            PhpdocxLogger::logger('Trailer not found', 'warning');
        }
        
        $trailer_str = $matches[1];

        // We create the object to parse (this is not innefficient, because it is disposed when returning from the function)
        //   and parse the trailer content.
        $parser = new PDFObjectParser();
        try {
            $trailer_obj = $parser->parsestr($trailer_str);
        } catch (Exception $e) {
            PhpdocxLogger::logger('Trailer is not valid', 'warning');
        }

        return $trailer_obj;
    }

    public static function build_xref_1_5($offsets) {
        if (isset($offsets[0])) unset($offsets[0]);
        $k = array_keys($offsets);
        sort($k);

        $indexes = [];
        $i_k = 0;
        $c_k = 0;
        $count = 1;
        $result = "";
        for ($i = 0; $i < count($k); $i++) {
            if ($c_k === 0) {
                $c_k = $k[$i] - 1;
                $i_k = $k[$i];
                $count = 0;
            }
            if ($k[$i] === $c_k + 1) {
                $count++;
            } else {
                array_push($indexes, "$i_k $count");
                $count = 1;
                $i_k = $k[$i];
            }
            $c_offset = $offsets[$k[$i]];

            if (is_array($c_offset)) {
                $result .= pack('C', 2);
                $result .= pack('N', $c_offset["stmoid"]);
                $result .= pack('C', $c_offset["pos"]);
            } else {
                if ($c_offset === null) {
                    $result .= pack('C', 0);
                    $result .= pack('N', $c_offset);
                    $result .= pack('C', 0);
                } else {
                    $result .= pack('C', 1);
                    $result .= pack('N', $c_offset);
                    $result .= pack('C', 0);
                }
            }
            $c_k = $k[$i];
        }
        array_push($indexes, "$i_k $count");
        $indexes = implode(" ", $indexes);

        return [
            "W" => [ 1, 4, 1 ],
            "Index" => $indexes,
            "stream" => $result
        ];
    }

    /**
     * This function obtains the xref from the cross reference streams (7.5.8 Cross-Reference Streams)
     *   which started in PDF 1.5.
     */
    public static function get_xref_1_5(&$_buffer, $xref_pos, $depth = null) {
        if ($depth !== null) {
            if ($depth <= 0) {
                return false;
            }

            $depth = $depth - 1;
        }

        $xref_o = PDFUtilFnc::find_object_at_pos($_buffer, null, $xref_pos, []);
        if ($xref_o === false) {
            PhpdocxLogger::logger('Cross reference object not found when parsing xref at position ' . $xref_pos, 'warning');
        }

        if (!(isset($xref_o["Type"])) || ($xref_o["Type"]->val() !== "XRef")) {
            PhpdocxLogger::logger('Invalid xref table', 'warning');
        }

        $stream = $xref_o->get_stream(false);
        if ($stream === null) {
            PhpdocxLogger::logger('Cross reference stream not found when parsing xref at position ' . $xref_pos, 'warning');
        }

        $W = $xref_o["W"]->val(true);
        if (count($W) !== 3) {
            PhpdocxLogger::logger('Invalid cross reference object', 'warning');
        }

        $W[0] = intval($W[0]);
        $W[1] = intval($W[1]);
        $W[2] = intval($W[2]);

        $Size = $xref_o["Size"]->get_int();
        if ($Size === false) {
            PhpdocxLogger::logger('Could not get the size of the xref table', 'warning');
        }

        $Index = [ 0, $Size ];
        if (isset($xref_o["Index"])) {
            $Index = $xref_o["Index"]->val(true);
        }

        if (count($Index) % 2 !== 0) {
            PhpdocxLogger::logger('Invalid indexes of xref table', 'warning');
        }

        // Get the previous xref table, to build up on it
        $trailer_obj = null;
        $xref_table = [];
        
        if (($depth === null) || ($depth > 0)) {
            // If still want to get more versions, let's check whether there is a previous xref table or not
            if (isset($xref_o["Prev"])) {
                $Prev = $xref_o["Prev"];
                $Prev = $Prev->get_int();
                if ($Prev === false) {
                    PhpdocxLogger::logger('Invalid reference to a previous xref table', 'warning');
                }

                // When dealing with 1.5 cross references, we do not allow to use other than cross references
                $valuesXref15 = PDFUtilFnc::get_xref_1_5($_buffer, $Prev);
                $xref_table = $valuesXref15[0];
                $trailer_obj = $valuesXref15[1];
            }
        }

        $stream_v = new StreamReader($stream);

        // Get the format function to un pack the values
        $get_fmt_function = function($f) {
            if ($f === false)
                return false;

            switch ($f) {
                case 0: return function ($v) { return 0; };
                case 1: return function ($v) { return unpack('C', str_pad($v, 1, chr(0), STR_PAD_LEFT))[1]; };
                case 2: return function ($v) { return unpack('n', str_pad($v, 2, chr(0), STR_PAD_LEFT))[1]; };
                case 3: 
                case 4: return function ($v) { return unpack('N', str_pad($v, 4, chr(0), STR_PAD_LEFT))[1]; };
                case 5:
                case 6:
                case 7:
                case 8: return function ($v) { return unpack('J', str_pad($v, 8, chr(0), STR_PAD_LEFT))[1]; };
            }
            return false;
        };

        $fmt_function = [
            $get_fmt_function($W[0]),
            $get_fmt_function($W[1]),
            $get_fmt_function($W[2])
        ];

        // Parse the stream according to the indexes and the W array
        $index_i = 0;
        while ($index_i < count($Index)) {
            $object_i = $Index[$index_i++];
            $object_count = $Index[$index_i++];

            while (($stream_v->currentchar() !== false) && ($object_count > 0)) {
                $f1 = $W[0]!=0?($fmt_function[0]($stream_v->nextchars($W[0]))):1;
                $f2 = $fmt_function[1]($stream_v->nextchars($W[1]));
                $f3 = $fmt_function[2]($stream_v->nextchars($W[2]));

                if (($f1 === false) || ($f2 === false) || ($f3 === false)) {
                    PhpdocxLogger::logger('Invalid stream for xref table', 'warning');
                }

                switch ($f1) {
                    case 0:
                        // Free object
                        $xref_table[$object_i] = null;
                        break;
                    case 1:
                        // Add object
                        $xref_table[$object_i] = $f2;
                        if ($f3 !== 0) {
                            PhpdocxLogger::logger('Do not know how to deal with non-zero generation objects', 'warning');
                        }
                        break;
                    case 2:
                        // Stream object
                        // $f2 is the number of a stream object, $f3 is the index in that stream object
                        $xref_table[$object_i] = array("stmoid" => $f2, "pos" => $f3 );
                        break;
                    default:
                        PhpdocxLogger::logger('Do not know about entry of type ' . $f1 . ' in xref table', 'warning');
                }

                $object_i++;
                $object_count--;
            }
        }

        return [ $xref_table, $xref_o->get_value(), "1.5" ];
    }

    public static function get_xref_1_4(&$_buffer, $xref_pos, $depth = null) {
        if ($depth !== null) {
            if ($depth <= 0)
                return false;

            $depth = $depth - 1;
        }

        $trailer_pos = strpos($_buffer, "trailer", $xref_pos);
        $min_pdf_version = "1.4";

        // Get the xref content and make sure that the buffer passed contains the xref tag at the offset provided
        $xref_substr = substr($_buffer, $xref_pos, $trailer_pos - $xref_pos);

        $separator = "\r\n";
        $xref_line = strtok($xref_substr, $separator);
        if ($xref_line !== 'xref') {
            PhpdocxLogger::logger('xref tag not found at position ' . $xref_pos, 'warning');
        }
        
        // Now parse the lines and build the xref table
        $obj_id = false;
        $obj_count = 0;
        $xref_table = [];

        while (($xref_line = strtok($separator)) !== false) {
            // The first type of entry contains the id of the next object and the amount of continuous objects defined
            if (preg_match('/([0-9]+) ([0-9]+)$/', $xref_line, $matches) === 1) {
                if ($obj_count > 0) {
                    // If still expecting objects, we'll assume that the xref is malformed
                    PhpdocxLogger::logger('Malformed xref at position ' . $xref_pos, 'warning');
                }
                $obj_id = intval($matches[1]);
                $obj_count = intval($matches[2]);
                continue;
            }

            // The other type of entry contains the offset of the object, the generation and the command (which is "f" for "free" or "n" for "new")
            if (preg_match('/^([0-9]+) ([0-9]+) (.)\s*/', $xref_line, $matches) === 1) {

                // If no object expected, we'll assume that the xref is malformed
                if ($obj_count === 0) {
                    PhpdocxLogger::logger('Unexpected entry for xref: ' . $xref_line, 'warning');
                }

                $obj_offset = intval($matches[1]);
                $obj_generation = intval($matches[2]);
                $obj_operation = $matches[3];

                if ($obj_offset !== 0) {
                    // TODO: Dealing with non-zero generation objects is a future work, as I do not know by now the implications of generation change
                    if (!(($obj_id === 0) && ($obj_generation === 65535))) {
                        if (intval($obj_generation) !== 0) {
                            //return p_error("SORRY, but do not know how to deal with non-0 generation objects", [false, false, false]);
                        }
                    }

                    // Make sure that the operation is one of those expected
                    switch ($obj_operation) {
                        case 'f':
                            $xref_table[$obj_id] = null;
                            break;
                        case 'n':
                            $xref_table[$obj_id] = $obj_offset;
                            break;
                        default:
                            // If it is not one of the expected, let's skip the object
                            PhpdocxLogger::logger('Invalid entry for xref: ' . $xref_line, 'warning');
                    }
                }

                $obj_count-= 1;
                $obj_id++;
                continue;
            }

            // If the entry is not recongised, show the error
            PhpdocxLogger::logger('Invalid xref entry ' . $xref_line, 'warning');
            $xref_line = strtok($separator);
        }

        // Get the trailer object
        $trailer_obj = PDFUtilFnc::get_trailer($_buffer, $trailer_pos);

        // If there exists a previous xref (for incremental PDFs), get it and merge the objects that do not exist in the current xref table
        if (isset($trailer_obj['Prev'])) {
            
            $xref_prev_pos = $trailer_obj['Prev']->val();
            if (!is_numeric($xref_prev_pos)) {
                PhpdocxLogger::logger('Invalid trailer ' . $trailer_obj, 'warning');
            }

            $xref_prev_pos = intval($xref_prev_pos);

            $valuesXref = PDFUtilFnc::get_xref_1_4($_buffer, $xref_prev_pos, $depth);
            $prev_table = $valuesXref[0];
            $prev_trailer = $valuesXref[1];
            $prev_min_pdf_version = $valuesXref[2];

            if ($prev_table !== false) {
                foreach ($prev_table as $obj_id => &$obj_offset) {
                    // If there not exists a new version, we'll acquire it
                    if (!isset($xref_table[$obj_id])) {
                        $xref_table[$obj_id] = $obj_offset;
                    }
                }
            }
        }

        return [ $xref_table, $trailer_obj, $min_pdf_version ];
    }

    public static function get_xref(&$_buffer, $xref_pos, $depth = null) {
        // Each xref is immediately followed by a trailer
        $trailer_pos = strpos($_buffer, "trailer", $xref_pos);
        if ($trailer_pos === false) {
            $valuesXref = PDFUtilFnc::get_xref_1_5($_buffer, $xref_pos, $depth);
        } else {
            $valuesXref = PDFUtilFnc::get_xref_1_4($_buffer, $xref_pos, $depth);
        }
        $xref_table = $valuesXref[0];
        $trailer_obj = $valuesXref[1];
        $min_pdf_version = $valuesXref[2];

        return [ $xref_table, $trailer_obj, $min_pdf_version ];
    }

    public static function acquire_structure(&$_buffer, $depth = null) {
        // Get the first line and acquire the PDF version of the document
        $separator = "\r\n";
        $pdf_version = strtok($_buffer, $separator);
        if ($pdf_version === false)
            return false;

        if (preg_match('/^%PDF-[0-9]+\.[0-9]+$/', $pdf_version, $matches) !== 1) {
            PhpdocxLogger::logger('PDF version string not found', 'warning');
        }

        $_versions=[];

        /*foreach ($matches as $match) {
            array_push($_versions, $match[2][1] . strlen($match[2][0]));
        }*/

        // Now get the trailing part and make sure that it has the proper form
        $startxref_pos = strrpos($_buffer, "startxref");
        if ($startxref_pos === false) {
            PhpdocxLogger::logger('startxref not found', 'warning');
        }

        if (preg_match('/startxref\s*([0-9]+)\s*%%EOF\s*$/ms', $_buffer, $matches, 0, $startxref_pos) !== 1) {
            PhpdocxLogger::logger('startxref and %%EOF not found', 'warning');
        }

        $xref_pos = intval($matches[1]);

        if ($xref_pos === 0) {
            // This is a dummy xref position from linearized documents
            return [
                "trailer" => false,
                "version" => substr($pdf_version, 1),
                "xref" => [],
                "xrefposition" => 0,
                "xrefversion" => substr($pdf_version, 1),
                "revisions" => $_versions
            ];    
        }

        $valuesXref = PDFUtilFnc::get_xref($_buffer, $xref_pos, $depth);
        $xref_table = $valuesXref[0];
        $trailer_object = $valuesXref[1];
        $min_pdf_version = $valuesXref[2];

        // We are providing a lot of information to be able to inspect the problems of a PDF file
        if ($xref_table === false) {
            // TODO: Maybe we could include a "recovery" method for this: if xref is not at pos $xref_pos, we could search for xref by hand
            PhpdocxLogger::logger('Could not find the xref table', 'warning');
        }

        if ($trailer_object === false) {
            PhpdocxLogger::logger('Could not find the trailer object', 'warning');
        }

        return [
            "trailer" => $trailer_object,
            "version" => substr($pdf_version, 1),
            "xref" => $xref_table,
            "xrefposition" => $xref_pos,
            "xrefversion" => $min_pdf_version,
            "revisions" => $_versions
        ];
    }

    /**
     * Signs a file using the certificate and key and obtains the signature content padded to the max signature length
     * @param filename the name of the file to sign
     * @param certificate the public key to sign
     * @param key the private key to sign
     * @param tmpfolder the folder in which to store a temporary file needed
     * @return signature the signature, in hexadecimal string, padded to the maximum length (i.e. for PDF) or false in case of error
     */
    public static function calculate_pkcs7_signature($filenametosign, $certificate, $key, $tmpfolder = "/tmp") {    
        $filesize_original = filesize($filenametosign);
        if ($filesize_original === false) {
            PhpdocxLogger::logger('Could not open file ' . $filenametosign, 'fatal');
        }

        $temp_filename = tempnam($tmpfolder, "pdfsign");

        if ($temp_filename === false) {
            PhpdocxLogger::logger('Could not create a temporary filename', 'fatal');
        }

        if (openssl_pkcs7_sign($filenametosign, $temp_filename, $certificate, $key, array(), PKCS7_BINARY | PKCS7_DETACHED) !== true) {
            unlink($temp_filename);
            PhpdocxLogger::logger('Failed to sign file ' . $filenametosign, 'fatal');
        }

        $signature = file_get_contents($temp_filename);
        // extract signature
        $signature = substr($signature, $filesize_original);
        $signature = substr($signature, (strpos($signature, "%%EOF\n\n------") + 13));

        $tmparr = explode("\n\n", $signature);
        $signature = $tmparr[1];
        // decode signature
        $signature = base64_decode(trim($signature));

        // convert signature to hex
        $signature = current(unpack('H*', $signature));
        $signature = str_pad($signature, __SIGNATURE_MAX_LENGTH, '0');

        // remove temp file
        unlink($temp_filename);

        return $signature;
    }   
    
    /**
     * Function that finds a the object at the specific position in the buffer
     * @param buffer the buffer from which to read the document
     * @param oid the target object id to read (if null, will return the first object, if found)
     * @param offset the offset at which the object is expected to be
     * @param xref_table the xref table, to be able to find indirect objects
     * @return obj the PDFObject obtained from the file or false if could not be found
     */
    public static function find_object_at_pos(&$_buffer, $oid, $object_offset, $xref_table) {

        $object = PDFUtilFnc::object_from_string($_buffer, $oid, $object_offset, $offset_end);

        if ($object === false) return false;

        $_stream_pending = false;

        // The distinction is required, because we need to get the proper start for the stream, and if using CRLF instead of LF
        //   - according to https://www.adobe.com/content/dam/acom/en/devnet/pdf/PDF32000_2008.pdf, stream is followed by CRLF 
        //     or LF, but not single CR.
        if (substr($_buffer, $offset_end - 7, 7) === "stream\n") {
            $_stream_pending = $offset_end;
        }

        if (substr($_buffer, $offset_end - 7, 8) === "stream\r\n") {
            $_stream_pending = $offset_end + 1;
        }

        // If it expects a stream, get it
        if ($_stream_pending !== false) {
            $length = $object['Length']->get_int();
            if ($length === false) {
                $length_object_id = $object['Length']->get_object_referenced();
                if ($length_object_id === false) {
                    PhpdocxLogger::logger('Could not get stream for object ' . $obj_id, 'warning');
                }
                $length_object = PDFUtilFnc::find_object($_buffer, $xref_table, $length_object_id);
                if ($length_object === false)
                    PhpdocxLogger::logger('Could not get object ' . $oid, 'warning');

                $length = $length_object->get_value()->get_int();
            }

            if ($length === false) {
                PhpdocxLogger::logger('Could not get stream length for object ' . $obj_id, 'warning');
            }

            $object->set_stream(substr($_buffer, $_stream_pending, $length), true);
        }

        return $object;
    }

    /**
     * Function that finds a specific object in the document, using the xref table as a base
     * @param buffer the buffer from which to read the document
     * @param xref_table the xref table
     * @param oid the target object id to read 
     * @return obj the PDFObject obtained from the file or false if could not be found
     */
    public static function find_object(&$_buffer, $xref_table, $oid) {
        
        if ($oid === 0) return false;
        if (!isset($xref_table[$oid])) return false;

        // Find the object and get where it ends
        $object_offset = $xref_table[$oid];

        if (!is_array($object_offset))
            return PDFUtilFnc::find_object_at_pos($_buffer, $oid, $object_offset, $xref_table);
        else {
            $object = PDFUtilFnc::find_object_in_objstm($_buffer, $xref_table, $object_offset["stmoid"], $object_offset["pos"], $oid);
            return $object;
        }  
    }

    /**
     * Function that searches for an object in an object stream
     */
    public static function find_object_in_objstm(&$_buffer, $xref_table, $objstm_oid, $objpos, $oid) {
        $objstm = PDFUtilFnc::find_object($_buffer, $xref_table, $objstm_oid);
        if ($objstm === false) {
            PhpdocxLogger::logger('Could not get object stream ' . $objstm_oid, 'warning');
        }

        if ((isset($objstm["Extends"]) ? $objstm["Extends"] : false) !== false) {
            // TODO: support them
            PhpdocxLogger::logger('Not supporting extended object streams at this time', 'warning');
        }

        $First = isset($objstm["First"]) ? $objstm["First"] : false;
        $N = isset($objstm["N"]) ? $objstm["N"] : false;
        $Type = isset($objstm["Type"]) ? $objstm["Type"] : false;
        
        if (($First === false) || ($N === false) || ($Type === false)) {
            PhpdocxLogger::logger('Invalid object stream ' . $objstm_oid, 'warning');
        }

        if ($Type->val() !== "ObjStm") {
            PhpdocxLogger::logger('Object ' . $objstm_oid . ' is not an object stream', 'warning');
        }

        $First = $First->get_int();
        $N = $N->get_int();

        $stream = $objstm->get_stream(false);
        $index = substr($stream, 0, $First);
        $index = explode(" ", trim($index));
        $stream = substr($stream, $First);

        if (count($index) % 2 !== 0) {
            PhpdocxLogger::logger('Invalid index for object stream ' . $objstm_oid, 'warning');
        }

        $objpos = $objpos * 2;
        if ($objpos > count($index)) {
            PhpdocxLogger::logger('Object ' . $oid . ' not found in object stream ' . $objstm_oid, 'warning');
        }

        $offset = intval($index[$objpos + 1]);
        $next = 0;
        $offsets = [];
        for ($i = 1; ($i < count($index)); $i = $i + 2)
            array_push($offsets, intval($index[$i]));

        array_push($offsets, strlen($stream));
        sort($offsets);
        for ($i = 0; ($i < count($offsets)) && ($offset >= $offsets[$i]); $i++);

        $next = $offsets[$i];

        $object_def_str = "$oid 0 obj " . substr($stream, $offset, $next - $offset) . " endobj";
        $object_def = PDFUtilFnc::object_from_string($object_def_str, $oid);
        return $object_def;
    }

    /**
     * Function that parses an object 
     */
    public static function object_from_string(&$buffer, $expected_obj_id, $offset = 0, &$offset_end = 0) {
        if (preg_match('/([0-9]+)\s+([0-9+])\s+obj(\s+)/ms', $buffer, $matches, 0, $offset) !== 1) {
            PhpdocxLogger::logger('Object is not valid: ' . $expected_obj_id, 'warning');
        }

        $found_obj_header = $matches[0];
        $found_obj_id = intval($matches[1]);
        $found_obj_generation = intval($matches[2]);

        if ($expected_obj_id === null)
            $expected_obj_id = $found_obj_id;

        if ($found_obj_id !== $expected_obj_id) {
            PhpdocxLogger::logger('pdf structure is corrupt: found obj ' . $found_obj_id . ' while searching for obj ' . $expected_obj_id . ' (at ' . $offset . ')', 'warning');
        }

        // The object starts after the header
        $offset = $offset + strlen($found_obj_header);

        // Parse the object
        $parser = new PDFObjectParser();

        $stream = new StreamReader($buffer, $offset);

        $obj_parsed = $parser->parse($stream);
        if ($obj_parsed === false) {
            PhpdocxLogger::logger('Object ' . $expected_obj_id . ' could not be parsed', 'warning');
        }

        switch ($parser->current_token()) {
            case PDFObjectParser::T_OBJECT_END:
                // The object has ended correctly
                break;
            case PDFObjectParser::T_STREAM_BEGIN:
                // There is an stream
                break;
            default:
                PhpdocxLogger::logger('Malformed object', 'warning');
        }

        $offset_end = $stream->getpos();
        return new PDFObject($found_obj_id, $obj_parsed, $found_obj_generation);
    }

    /**
     * Builds the xref for the document, using the list of objects
     * @param offsets an array indexed by the oid of the objects, with the offset of each
     *  object in the document.
     * @return xref_string a string that contains the xref table, ready to be inserted in the document
     */
    public static function build_xref($offsets) {
        $k = array_keys($offsets);
        sort($k);

        $i_k = 0;
        $c_k = 0;
        $count = 1;
        $result = "";
        $references = "0000000000 65535 f \n";
        for ($i = 0; $i < count($k); $i++) {
            if ($k[$i] === 0) continue;
            if ($k[$i] === $c_k + 1) {
                $count++;
            } else {
                $result = $result . "$i_k ${count}\n$references";
                $count = 1;
                $i_k = $k[$i];
                $references = "";
            }
            $references .= sprintf("%010d 00000 n \n", $offsets[$k[$i]]);
            $c_k = $k[$i];
        }
        $result = $result . "$i_k ${count}\n$references";

        return "xref\n$result";            
    }    
}

if (!defined('__CONVENIENT_MAX_BUFFER_DUMP')) {
    define('__CONVENIENT_MAX_BUFFER_DUMP', 80);
}

/**
 * This function creates the objects needed to add an image to the document, at a specific position and size.
 *   The function is agnostic from the place in which the image is to be created, and just creates the objects
 *   with its contents and prepares the PDF command to place the image
 * @param filename the file name that contains the image, or a string that contains the image (with character '@'
 *                 prepended)
 * @param x points from left in which to appear the image (the units are "content-defined" (i.e. depending on the size of the page))
 * @param y points from bottom in which to appear the image (the units are "content-defined" (i.e. depending on the size of the page))
 * @param w width of the rectangle in which to appear the image (image will be scaled, and the units are "content-defined" (i.e. depending on the size of the page))
 * @param h height of the rectangle in which to appear the image (image will be scaled, and the units are "content-defined" (i.e. depending on the size of the page))
 * @return result an array with the next fields:
 *                  "images": objects of the corresponding images (i.e. position [0] is the image, the rest elements are masks, if needed)
 *                  "resources": PDFValueObject with keys that needs to be incorporated to the resources of the object in which the images will appear
 *                  "alpha": true if the image has alpha
 *                  "command": pdf command to draw the image
 */
function _add_image($object_factory, $filename, $x=0, $y=0, $w=0, $h=0) {

    if (empty($filename)) {
        PhpdocxLogger::logger('Invalid image name or stream', 'fatal');
    }

    if ($filename[0] === '@') {
        $filecontent = substr($filename, 1);
    } else {
        $filecontent = @file_get_contents($filename);

        if ($filecontent === false) {
            PhpdocxLogger::logger('Failed to get the image', 'fatal');
        }
    }

    $finfo = new \finfo();
    $content_type = $finfo->buffer($filecontent, FILEINFO_MIME_TYPE);

    $ext = mime_to_ext($content_type);

    // TODO: support more image types than jpg
    $add_alpha = false;
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $info = _parsejpg($filecontent);
            break;
        case 'png':
            $add_alpha = true;
            $info = _parsepng($filecontent);
            break;
        default:
            PhpdocxLogger::logger('Unsupported mime type', 'fatal');
    }

    // Generate a new identifier for the image
    $info['i'] = "Im" . get_random_string(4);

    if ($w === null)
        $w = -96;
    if ($h === null)
        $h = -96;

    if($w<0)
        $w = -$info['w']*72/$w;
    if($h<0)
        $h = -$info['h']*72/$h;
    if($w==0)
        $w = $h*$info['w']/$info['h'];
    if($h==0)
        $h = $w*$info['h']/$info['w'];

    $images_objects = _create_image_objects($info, $object_factory);

    // Generate the command to translate and scale the image
    $data = sprintf("q %.2F 0 0 %.2F %.2F %.2F cm /%s Do Q", $w, $h, $x, $y, $info['i']);

    $resources = new PDFValueObject( [
        'ProcSet' => [ '/PDF', '/Text', '/ImageB', '/ImageC', '/ImageI' ],
        'XObject' => new PDFValueObject ([
            $info['i'] => new PDFValueReference($images_objects[0]->get_oid()),                        
        ])
    ]);

    return [ "image" => $images_objects[0], 'command' => $data, 'resources' => $resources, 'alpha' => $add_alpha ];
}

/**
 * Creates an image object in the document, using the content of "info"
 *   NOTE: the image inclusion is taken from http://www.fpdf.org/; this is a translation
 *         of function _putimage
 */
function _create_image_objects($info, $object_factory) { 
    $objects = [];

    $image = call_user_func($object_factory,
        [
            'Type' => '/XObject',
            'Subtype' => '/Image',
            'Width' => $info['w'],
            'Height' => $info['h'],
            'ColorSpace' => [ ],
            'BitsPerComponent' => $info['bpc'],
            'Length' => strlen($info['data']),
        ]            
    );

    switch ($info['cs']) {
        case 'Indexed':
            $data = gzcompress($info['pal']);
            $streamobject = call_user_func($object_factory, [
                'Filter' => '/FlateDecode',
                'Length' => strlen($data),
            ]);
            $streamobject->set_stream($data);

            $image['ColorSpace']->push([
                '/Indexed', '/DeviceRGB', (strlen($info['pal']) / 3) - 1, new PDFValueReference($streamobject->get_oid())
            ]);
            array_push($objects, $streamobject);
            break;
        case 'DeviceCMYK':
            $image["Decode"] = new PDFValueList([1, 0, 1, 0, 1, 0, 1, 0]);
        default:
            $image['ColorSpace'] = new PDFValueType( $info['cs'] );
            break;
    }

    if (isset($info['f']))
        $image['Filter'] = new PDFValueType($info['f']);

    if(isset($info['dp']))
        $image['DecodeParms'] = PDFValueObject::fromstring($info['dp']);

    if (isset($info['trns']) && is_array($info['trns']))
        $image['Mask'] = new PDFValueList($info['trns']);

    if (isset($info['smask'])) {
        $smaskinfo = [
            'w' => $info['w'], 
            'h' => $info['h'], 
            'cs' => 'DeviceGray', 
            'bpc' => 8, 
            'f' => $info['f'], 
            'dp' => '/Predictor 15 /Colors 1 /BitsPerComponent 8 /Columns '.$info['w'],
            'data' => $info['smask']
        ];

        // In principle, it may return multiple objects
        $smasks = _create_image_objects($smaskinfo, $object_factory);
        foreach ($smasks as $smask)
            array_push($objects, $smask);
        $image['SMask'] = new PDFValueReference($smask->get_oid());
    }

    $image->set_stream($info['data']);
    array_unshift($objects, $image);

    return $objects;
}

/** 
 * Outputs a var to a string, using the PHP var_dump function
 * @param var the variable to output
 * @return output the result of the var_dump of the variable
*/
function var_dump_to_string($var) {
    ob_start();
    var_dump($var);
    $result = ob_get_clean();
    return $result;
}
/**
 * Outputs a set of vars to a string, that is returned
 * @param vars the vars to dump
 * @return str the var_dump output of the variables
 */
function debug_var(...$vars) {
    $result = [];
    foreach ($vars as $var) {
        array_push($result, var_dump_to_string($var));
    }
    return implode("\n", $result);
}

/**
 * Function that converts an array into a string, but also recursively converts its values
 *   just in case that they are also arrays. In case that it is not an array, it returns its
 *   string representation
 * @param e the variable to convert
 * @return str the string representation of the array
 */
function varval($e) {
    $retval = $e;
    if (is_array($e)) {
        $a = [];
        foreach ($e as $k => $v) {
            $v = varval($v);
            array_push($a, "$k => $v");
        }
        $retval = "[ " . implode(", ", $a) . " ]";
    }
    return $retval;
}

/**
 * Obtains a random string from a printable character set: alphanumeric, extended with
 *   common symbols, an extended with less common symbols.
 * Note: does not consider space (0x20) nor delete (0x7f) for the alphabet. All the
 *   other printable ascii chars are considered
 * @param length length of the resulting random string (default: 8)
 * @param extended true if the alphabet should consider also the common symbols (e.g. :,(...))
 * @param hard true if the alphabet should consider also the hard symbols: ^`|~ (which use to 
 *      need more than one key to be written)
 * @return random_string a random string considering the alphabet
 */
function get_random_string($length = 8, $extended = false, $hard = false){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    if ($extended === true) {
        $codeAlphabet .= "!\"#$%&'()*+,-./:;<=>?@[\\]_{}";
    }
    if ($hard === true) {
        $codeAlphabet .= "^`|~";
    }
    $max = strlen($codeAlphabet);
    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[rand(0, $max-1)];
    }
   return $token;
}   

function show_bytes($str, $columns = null) {
    $result = "";
    if ($columns === null)
        $columns = strlen($str);
    $c = $columns;
    for ($i = 0; $i < strlen($str); $i++) {
        $result .= sprintf("%02x ", ord($str[$i]));
        $c--;
        if ($c === 0) {
            $c = $columns;
            $result .= "\n";
        }

    }
    return $result;
}

/**
 * Function that outputs a timestamp to a PDF compliant string (including the D:)
 * @param timestamp the timestamp to conver (or 0 if get "now")
 * @return date_string the date string in PDF format
 */
function timestamp_to_pdfdatestring($date = null) {
    if ($date === null) {
        $date = new \DateTime();
    }

    $timestamp = $date->getTimestamp();
    return 'D:' . get_pdf_formatted_date($timestamp);
}
/**
 * Returns a formatted date-time.
 * @param $time (int) Time in seconds.
 * @return string escaped date string.
 * @since 5.9.152 (2012-03-23)
 */
function get_pdf_formatted_date($time) {
    return substr_replace(date('YmdHisO', intval($time)), '\'', (0 - 2), 0).'\'';
}

function mime_to_ext($mime) {
    $mime_map = [
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'application/x-compressed'                                                  => '7zip',
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'application/postscript'                                                    => 'ai',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'application/x-troff-msvideo'                                               => 'avi',
        'application/macbinary'                                                     => 'bin',
        'application/mac-binary'                                                    => 'bin',
        'application/x-binary'                                                      => 'bin',
        'application/x-macbinary'                                                   => 'bin',
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'application/mac-compactpro'                                                => 'cpt',
        'application/pkix-crl'                                                      => 'crl',
        'application/pkcs-crl'                                                      => 'crl',
        'application/x-x509-ca-cert'                                                => 'crt',
        'application/pkix-cert'                                                     => 'crt',
        'text/css'                                                                  => 'css',
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/x-director'                                                    => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/x-dvi'                                                         => 'dvi',
        'message/rfc822'                                                            => 'eml',
        'application/x-msdownload'                                                  => 'exe',
        'video/x-f4v'                                                               => 'f4v',
        'audio/x-flac'                                                              => 'flac',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/gpg-keys'                                                      => 'gpg',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/mac-binhex40'                                                  => 'hqx',
        'application/mac-binhex'                                                    => 'hqx',
        'application/x-binhex40'                                                    => 'hqx',
        'application/x-mac-binhex40'                                                => 'hqx',
        'text/html'                                                                 => 'html',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'application/java-archive'                                                  => 'jar',
        'application/x-java-application'                                            => 'jar',
        'application/x-jar'                                                         => 'jar',
        'image/jp2'                                                                 => 'jp2',
        'video/mj2'                                                                 => 'jp2',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpg',
        'image/pjpeg'                                                               => 'jpg',
        'application/x-javascript'                                                  => 'js',
        'application/json'                                                          => 'json',
        'text/json'                                                                 => 'json',
        'application/vnd.google-earth.kml+xml'                                      => 'kml',
        'application/vnd.google-earth.kmz'                                          => 'kmz',
        'text/x-log'                                                                => 'log',
        'audio/x-m4a'                                                               => 'm4a',
        'audio/mp4'                                                                 => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'application/vnd.mif'                                                       => 'mif',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/oda'                                                           => 'oda',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogg',
        'application/ogg'                                                           => 'ogg',
        'application/x-pkcs10'                                                      => 'p10',
        'application/pkcs10'                                                        => 'p10',
        'application/x-pkcs12'                                                      => 'p12',
        'application/x-pkcs7-signature'                                             => 'p7a',
        'application/pkcs7-mime'                                                    => 'p7c',
        'application/x-pkcs7-mime'                                                  => 'p7c',
        'application/x-pkcs7-certreqresp'                                           => 'p7r',
        'application/pkcs7-signature'                                               => 'p7s',
        'application/pdf'                                                           => 'pdf',
        'application/octet-stream'                                                  => 'pdf',
        'application/x-x509-user-cert'                                              => 'pem',
        'application/x-pem-file'                                                    => 'pem',
        'application/pgp'                                                           => 'pgp',
        'application/x-httpd-php'                                                   => 'php',
        'application/php'                                                           => 'php',
        'application/x-php'                                                         => 'php',
        'text/php'                                                                  => 'php',
        'text/x-php'                                                                => 'php',
        'application/x-httpd-php-source'                                            => 'php',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'audio/x-realaudio'                                                         => 'ra',
        'audio/x-pn-realaudio'                                                      => 'ram',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'audio/x-pn-realaudio-plugin'                                               => 'rpm',
        'application/x-pkcs7'                                                       => 'rsa',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'video/vnd.rn-realvideo'                                                    => 'rv',
        'application/x-stuffit'                                                     => 'sit',
        'application/smil'                                                          => 'smil',
        'text/srt'                                                                  => 'srt',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'image/tiff'                                                                => 'tiff',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/videolan'                                                      => 'vlc',
        'text/vtt'                                                                  => 'vtt',
        'audio/x-wav'                                                               => 'wav',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',
        'application/wbxml'                                                         => 'wbxml',
        'video/webm'                                                                => 'webm',
        'audio/x-ms-wma'                                                            => 'wma',
        'application/wmlc'                                                          => 'wmlc',
        'video/x-ms-wmv'                                                            => 'wmv',
        'video/x-ms-asf'                                                            => 'wmv',
        'application/xhtml+xml'                                                     => 'xhtml',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',
        'application/xspf+xml'                                                      => 'xspf',
        'application/x-compress'                                                    => 'z',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',
        'text/x-scriptzsh'                                                          => 'zsh',
    ];
    return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
}    

/**
 * This class abstracts the reading from a stream of data (i.e. a string). The objective of
 *   using this class is to enable the creation of other classes (e.g. FileStreamReader) to
 *   read from other char streams (e.g. a file)
 * 
 * The class gets a string that will be used as the buffer to read, and then it is possible
 *   to sequentially get the characters from the string using funcion *nextchar*, that will
 *   return "false" when the stream is finished.
 * 
 * Other functions to change the position are also available (e.g. goto)
 */
class StreamReader {
    protected $_buffer = "";
    protected $_bufferlen = 0;
    protected $_pos = 0;

    public function __construct($string = null, $offset = 0) {
        if ($string === null)
            $string = "";

        $this->_buffer = $string;
        $this->_bufferlen = strlen($string);
        $this->gotopos($offset);
    }

    /**
     * Advances the buffer to the next char and returns it
     * @return char the next char in the buffer
     * 
     */
    public function nextchar() {
        $this->_pos = min($this->_pos + 1, $this->_bufferlen);
        return $this->currentchar();
    }

    /**
     * Advances the buffer to the next n chars and returns them
     * @param n the number of chars to read
     * @return str the substring obtained (with at most, n chars)
     */
    public function nextchars($n) {
        $n = min($n, $this->_bufferlen - $this->_pos);
        $retval = substr($this->_buffer, $this->_pos, $n);
        $this->_pos += $n;
        return $retval;
    }

    /**
     * Returns the current char
     * @return char the current char
     */
    public function currentchar() {
        if ($this->_pos >= $this->_bufferlen)
            return false;

        return $this->_buffer[$this->_pos];
    }

    /**
     * Returns whether the stream has finished or not
     * @return finished true if there are no more chars to read from the stream; false otherwise
     */
    public function eos() {
        return $this->_pos >= $this->_bufferlen;
    }

    /**
     * Sets the position of the buffer to the position in the parameter
     * @param pos the position to which the buffer must be set
     */
    public function gotopos($pos = 0) {
        $this->_pos = min(max(0, $pos), $this->_bufferlen);
    }

    /**
     * Obtains a substring that begins at current position.
     * @param length length of the substring to obtain (0 or <0 will obtain the whole buffer from the current position)
     * @return substr the substring
     */
    public function substratpos($length = 0) {
        if ($length > 0)
            return substr($this->_buffer, $this->_pos, $length);
        else
            return substr($this->_buffer, $this->_pos);
    }

    /**
     * Gets the current position of the buffer
     * @return position the position of the buffer
     */
    public function getpos() {
        return $this->_pos;
    }

    /**
     * Obtains the size of the buffer
     * @return size the size of the buffer
     */
    public function size() {
        return $this->_bufferlen;
    }
}

class UUID {
  public static function v3($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = md5($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 3
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

  public static function v5($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = sha1($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 5
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function is_valid($uuid) {
    return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
  }
}

class PDFValue implements \ArrayAccess {
    protected $value = null;
    public function __construct($v) {
        $this->value = $v;
    }
    public function val() {
        return $this->value;
    }
    public function __toString() {
        return "" . $this->value;
    }
    public function offsetExists ( $offset ) {
        if (!is_array($this->value)) return false;
        return isset($this->value[$offset]);
    }
    public function offsetGet ( $offset ) {
        if (!is_array($this->value)) return false;
        if (!isset($this->value[$offset])) return false;
        return $this->value[$offset];
    }
    public function offsetSet($offset , $value ) {
        if (!is_array($this->value)) return false;
        $this->value[$offset] = $value;
    }
    public function offsetUnset($offset ) {
        if ((!is_array($this->value)) || (!isset($this->value[$offset]))) {
            PhpdocxLogger::logger('Invalid offset', 'fatal');
        }
        unset($this->value[$offset]);
    }    
    public function push($v) {
        /*if (get_class($v) !== get_class($this))
            throw new \Exception('invalid object to concat to this one');*/
        return false;
    }
    public function get_int() {
        return false;
    }
    public function get_object_referenced() {
        return false;
    }  
    public function get_keys() {
        return false;
    }
    /**
     * Function that converts standard types into PDFValue* types
     *  - integer, double are translated into PDFValueSimple
     *  - string beginning with /, is translated into PDFValueType
     *  - string without separator (e.g. "\t\n ") are translated into PDFValueSimple
     *  - other strings are translated into PDFValueString
     *  - array is translated into PDFValueList, and its inner elements are also converted.
     * @param value a standard php object (e.g. string, integer, double, array, etc.)
     * @return pdfvalue an object of type PDFValue*, depending on the 
     */
    protected static function _convert($value) {
        switch (gettype($value)) {
            case 'integer':
            case 'double':
                $value = new PDFValueSimple($value);
                break;
            case 'string':
                if ($value[0] === '/')
                    $value = new PDFValueType(substr($value, 1));
                else
                    if (preg_match("/\s/ms", $value) === 1)
                        $value = new PDFValueString($value); 
                    else
                        $value = new PDFValueSimple($value); 
                break;
            case 'array': 
                if (count($value) === 0) {
                    // An empty list is assumed to be a list
                    $value = new PDFValueList();
                } else {

                    // Try to parse it as an object (i.e. [ 'Field' => 'Value', ...])
                    $obj = PDFValueObject::fromarray($value);            
                    if ($obj !== false)
                        $value = $obj;
                    else {

                        // If not an object, it is a list
                        $list = [];
                        foreach ($value as $v) {
                            array_push($list, self::_convert($v));
                        }
                        $value = new PDFValueList($list);
                    }
                }
                break;
        }
        return $value;
    }     
}

class PDFValueList extends PDFValue {
    public function __construct($value = []) {
        parent::__construct($value);
    }
    public function __toString() {
        return '[' . implode(' ', $this->value) . ']';
    }
    public function val($recurse = false) {
        if ($recurse === true) {
            $result = [];
            foreach ($this->value as $v) {
                array_push($result, $v->val());
            }
            return $result;
        } else
            return parent::val();
    }
    public function get_object_referenced() {
        $ids = [];
        $plain_text_val = implode(' ', $this->value);
        if (trim($plain_text_val) !== "") {
            if (preg_match_all('/(([0-9]+)\s+[0-9]+\s+R)[^0-9]*/ms', $plain_text_val, $matches) > 0) {
                $rebuilt = implode(" ", $matches[0]);
                $rebuilt = preg_replace('/\s+/ms', ' ', $rebuilt);
                $plain_text_val = preg_replace('/\s+/ms', ' ', $plain_text_val);
                if ($plain_text_val === $rebuilt) {
                    // Any content is a reference
                    foreach ($matches[2] as $id)
                        array_push($ids, intval($id));
                } 
            } else
                return false;
        }
        return $ids;
    }
    public function push($v) {
        if (is_object($v) && (get_class($v) === get_class($this))) {
            // If a list is pushed to another list, the elements are merged
            $v = $v->val();
        }
        if (!is_array($v)) $v = [ $v ];
        foreach ($v as $e) {
            $e = self::_convert($e);
            array_push($this->value, $e);
        }
        return true;
    }
}

class PDFValueObject extends PDFValue {
    public function __construct($value = []) {
        $result = [];
        foreach ($value as $k => $v) {
            $result[$k] = self::_convert($v);
        }
        parent::__construct($result);
    }

    public static function fromarray($parts) {
        $k = array_keys($parts);
        $intkeys = false;
        $result = [];
        foreach ($k as $ck)
            if (is_int($ck)) {
                $intkeys = true;
                break;
            }
        if ($intkeys) return false;
        foreach ($parts as $k => $v) {
            $result[$k] = self::_convert($v);
        }
        return new PDFValueObject($result);
    }

    public static function fromstring($str) {
        $result = [];
        $field = null;
        $value = null;
        $parts = explode(' ', $str);
        for ($i = 0; $i < count($parts); $i++) {
            if ($field === null) {
                $field = $parts[$i];
                if ($field === '') return false;
                if ($field[0] !== '/') return false;
                $field = substr($field, 1);
                if ($field === '') return false;
                continue;
            }
            $value = $parts[$i];
            $result[$field] = $value;
            $field = null;
        }
        // If there is no pair of values, there is no valid
        if ($field !== null) return false;
        return new PDFValueObject($result);
    }

    public function get_keys() {
        return array_keys($this->value);
    }

    /**
     * Function used to enable using [x] to set values to the fields of the object (from ArrayAccess interface)
     *  i.e. object[offset]=value
     * @param offset the index used inside the braces
     * @param value the value to set to that index (it will be converted to a PDFValue* object)
     * @return value the value set to the field
     */
    public function offsetSet($offset , $value) {
        if ($value === null) {
            if (isset($this->value[$offset]))
                unset($this->value[$offset]);
            return null;
        }
        $this->value[$offset] = self::_convert($value);
        return $this->value[$offset];
    }
    public function offsetExists ( $offset ) {
        return isset($this->value[$offset]);
    }

    /**
     * Function to output the object using the PDF format, and trying to make it compact (by reducing spaces, depending on the values)
     * @return pdfentry the PDF entry for the object
     */
    public function __toString() {
        $result = [];
        foreach ($this->value as $k => $v) {
            $v = "" . $v;
            if ($v === "") {
                array_push($result, "/$k");
                continue;
            }
            switch ($v[0]) {
                case '/':
                case '[':
                case '(':
                case '<':
                    array_push($result, "/$k$v");
                    break;
                default:
                    array_push($result, "/$k $v");
            }
        }
        return "<<" . implode('', $result) . ">>";
    }
}

class PDFValueSimple extends PDFValue {
    public function __construct($v) {
        parent::__construct($v);
    }
    public function push($v) {
        if (get_class($v) === get_class($this)) {
            // Can push
            $this->value = $this->value . ' ' . $v->val();
            return true;
        }
        return false;
    }
    public function get_object_referenced() {
        if (! preg_match('/^\s*([0-9]+)\s+([0-9]+)\s+R\s*$/ms', $this->value, $matches)) {
            return false;
        }
        return intval($matches[1]);
    }
    public function get_int() {
        if (! is_numeric($this->value)) return false;
        return intval($this->value);
    }
}

class PDFValueReference extends PDFValueSimple {
    public function __construct($oid) {
        parent::__construct(sprintf("%d 0 R", $oid));
    }
};

class PDFValueString extends PDFValue {
    public function __toString() {
        return "(" . $this->value . ")";
    }
}

class PDFValueType extends PDFValue {
    public function __toString() {
        return "/" . trim($this->value);
    }
}

class PDFValueHexString extends PDFValueString {
    public function __toString() {
        return "<" . trim($this->value) . ">";
    }
}

function _parsejpg($filecontent)
{
    // Extract info from a JPEG file
    $a = getimagesizefromstring($filecontent);
    if(!$a) {
        PhpdocxLogger::logger('Missing or incorrect image', 'fatal');
    }
    if($a[2]!=2)
        return perror('Not a JPEG image');
    if(!isset($a['channels']) || $a['channels']==3)
        $colspace = 'DeviceRGB';
    elseif($a['channels']==4)
        $colspace = 'DeviceCMYK';
    else
        $colspace = 'DeviceGray';
    $bpc = isset($a['bits']) ? $a['bits'] : 8;
    $data = $filecontent;
    return array('w'=>$a[0], 'h'=>$a[1], 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'DCTDecode', 'data'=>$data);
}

function _parsepng($filecontent)
{
    // Extract info from a PNG file
    $f = new StreamReader($filecontent);
    $info = _parsepngstream($f);
    return $info;
}

function _parsepngstream(&$f)
{
    // Check signature
    if(($res=_readstream($f,8))!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)) {
        PhpdocxLogger::logger('Not a PNG image', 'fatal');
    }

    // Read header chunk
    _readstream($f,4);
    if (_readstream($f,4)!='IHDR') {
        PhpdocxLogger::logger('Incorrect PNG image', 'fatal');
    }
    $w = _readint($f);
    $h = _readint($f);
    $bpc = ord(_readstream($f,1));
    if ($bpc>8) {
        PhpdocxLogger::logger('16-bit depth not supported', 'fatal');
    }
    $ct = ord(_readstream($f,1));
    if ($ct==0 || $ct==4) {
        $colspace = 'DeviceGray';
    }
    elseif ($ct==2 || $ct==6) {
        $colspace = 'DeviceRGB';
    }
    elseif ($ct==3) {
        $colspace = 'Indexed';
    }
    else {
        PhpdocxLogger::logger('Unknown color type', 'fatal');
    }
    if (ord(_readstream($f,1))!=0) {
        PhpdocxLogger::logger('Unknown compression method', 'fatal');
    }
    if (ord(_readstream($f,1))!=0) {
        PhpdocxLogger::logger('Unknown filter method', 'fatal');
    }
    if (ord(_readstream($f,1))!=0) {
        PhpdocxLogger::logger('Interlacing not supported', 'fatal');
    }
    _readstream($f,4);
    $dp = '/Predictor 15 /Colors '.($colspace=='DeviceRGB' ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w;

    // Scan chunks looking for palette, transparency and image data
    $pal = '';
    $trns = '';
    $data = '';
    do
    {
        $n = _readint($f);
        $type = _readstream($f,4);
        if($type=='PLTE')
        {
            // Read palette
            $pal = _readstream($f,$n);
            _readstream($f,4);
        }
        elseif($type=='tRNS')
        {
            // Read transparency info
            $t = _readstream($f,$n);
            if($ct==0)
                $trns = array(ord(substr($t,1,1)));
            elseif($ct==2)
                $trns = array(ord(substr($t,1,1)), ord(substr($t,3,1)), ord(substr($t,5,1)));
            else
            {
                $pos = strpos($t,chr(0));
                if($pos!==false)
                    $trns = array($pos);
            }
            _readstream($f,4);
        }
        elseif($type=='IDAT')
        {
            // Read image data block
            $data .= _readstream($f,$n);
            _readstream($f,4);
        }
        elseif($type=='IEND')
            break;
        else
            _readstream($f,$n+4);
    }
    while($n);

    if($colspace=='Indexed' && empty($pal)) {
        PhpdocxLogger::logger('Missing palette in image', 'fatal');
    }
    $info = array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'dp'=>$dp, 'pal'=>$pal, 'trns'=>$trns);
    if($ct>=4)
    {
        // Extract alpha channel
        if(!function_exists('gzuncompress')) {
            PhpdocxLogger::logger('Zlib not available, can\'t handle alpha channel', 'fatal');
        }
        $data = gzuncompress($data);
        if ($data === false) {
            PhpdocxLogger::logger('Failed to uncompress the image', 'fatal');
        }
        $color = '';
        $alpha = '';
        if($ct==4)
        {
            // Gray image
            $len = 2*$w;
            for($i=0;$i<$h;$i++)
            {
                $pos = (1+$len)*$i;
                $color .= $data[$pos];
                $alpha .= $data[$pos];
                $line = substr($data,$pos+1,$len);
                $color .= preg_replace('/(.)./s','$1',$line);
                $alpha .= preg_replace('/.(.)/s','$1',$line);
            }
        }
        else
        {
            // RGB image
            $len = 4*$w;
            for($i=0;$i<$h;$i++)
            {
                $pos = (1+$len)*$i;
                $color .= $data[$pos];
                $alpha .= $data[$pos];
                $line = substr($data,$pos+1,$len);
                $color .= preg_replace('/(.{3})./s','$1',$line);
                $alpha .= preg_replace('/.{3}(.)/s','$1',$line);
            }
        }
        unset($data);
        $data = gzcompress($color);
        $info['smask'] = gzcompress($alpha);
        /*
        $this->WithAlpha = true;
        if($this->PDFVersion<'1.4')
            $this->PDFVersion = '1.4';
            */
    }
    $info['data'] = $data;
    return $info;
}

function _readstream(&$f, $n) {
    $res = "";

    while ($n>0 && !$f->eos()) {
        $s = $f->nextchars($n);
        if ($s === false) {
            PhpdocxLogger::logger('Error while reading the stream', 'warning');
        }
        $n -= strlen($s);
        $res .= $s;
    }

    if ($n>0) {
        PhpdocxLogger::logger('Unexpected end of stream', 'warning');
    }
    return $res;
}

function _readint(&$f)
{
    // Read a 4-byte integer from stream
    $a = unpack('Ni',_readstream($f,4));
    return $a['i'];
}
