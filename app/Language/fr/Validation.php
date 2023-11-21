<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

// Validation language settings
return [
	// Core Messages
	'ruleNotFound'    => '{0} n\'est pas une règle valide.',
	'groupNotFound'   => '{0} n\'est pas un groupe de règles de validation.',
	'groupNotArray'   => 'Le groupe de règles {0} doit être un tableau.',
	'invalidTemplate' => '{0} n\'est pas un modèle de Validation valide.',

	// Rule Messages
	'alpha'                 => 'Ce champ ne peut contenir que des caractères alphabétiques.',
	'alpha_dash'            => 'Ce champ ne peut contenir que des caractères alphanumériques, des underscores, et des tirets.',
	'alpha_numeric'         => 'Ce champ ne peut contenir que des caractères alphanumériques.',
	'alpha_numeric_space'   => 'Ce champ ne peut contenir que des caractères alphanumériques et des espaces.',
	'alpha_space'           => 'Ce champ ne peut contenir que des caractères alphabétiques et des espaces.',
	'decimal'               => 'Ce champ doit contenir un nombre décimal.',
	'differs'               => 'Ce champ doit être différent du champ {param}.',
	'exact_length'          => 'Ce champ doit avoir précisément {param} caractères de long.',
	'greater_than'          => 'Ce champ doit contenir un nombre plus grand que {param}.',
	'greater_than_equal_to' => 'Ce champ doit être supérieur ou égal à {param}.',
	'in_list'               => 'Ce champ doit être un élément de la liste suivante : {param}.',
	'hex'                   => 'Ce champ ne peut contenir que des caractères hexadécimaux.',
	'integer'               => 'Ce champ doit contenir un nombre entier.',
	'is_natural'            => 'Ce champ ne doit contenir que des chiffres.',
	'is_natural_no_zero'    => 'Ce champ ne doit contenir que des chiffres et être supérieur à zéro.',
	'is_unique'             => 'Ce champ doit contenir une valeur unique.',
	'less_than'             => 'Ce champ doit contenir un nombre inférieur à {param}.',
	'less_than_equal_to'    => 'Ce champ doit contenir un nombre inférieur ou égal à {param}.',
	'matches'               => 'Ce champ ne coïncide pas avec Ce champ {param}.',
	'max_length'            => 'Ce champ ne peut pas dépasser une longueur de {param} caractères.',
	'min_length'            => 'Ce champ doit contenir au moins {param} caractères.',
	'numeric'               => 'Ce champ ne doit contenir que des nombres.',
	'regex_match'           => 'Ce champ n\'a pas le format attendu.',
	'required'              => 'Ce champ est obligatoire.',
	'required_with'         => 'Ce champ est obligatoire lorsque {param} est présent.',
	'required_without'      => 'Ce champ est obligatoire lorsque {param} n\'est pas présent.',
	'timezone'              => 'Ce champ doit être un fuseau horaire valide.',
	'valid_base64'          => 'Ce champ doit être une chaîne de caractères en base64 valide.',
	'valid_email'           => 'Ce champ doit contenir une adresse email valide.',
	'valid_emails'          => 'Ce champ doit contenir des adresses email valides.',
	'valid_ip'              => 'Ce champ doit contenir une IP valide.',
	'valid_url'             => 'Ce champ doit contenir une URL valide.',
	'valid_date'            => 'Ce champ doit contenir une date valide.',

	// Credit Cards
	'valid_cc_num' => 'ne semble pas être un numéro de carte de crédit valide.',

	// Files
	'uploaded' => 'Le fichier envoyé n\'est pas valide.',
	'max_size' => 'Le fichier est trop volumineux.',
	'is_image' => 'Le fichier envoyé n\'est pas une image valide.',
	'mime_in'  => 'n\'a pas un type MIME valide.',
	'ext_in'   => 'L\'extension du fichier  n\'est pas valide.',
	'max_dims' => 'Soit n\'est pas une image, soit elle est trop haute ou trop large.',
];
