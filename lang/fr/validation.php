<?php

return [
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'url' => 'Le champ :attribute doit être une URL valide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'string' => 'Le champ :attribute doit être du texte.',
    'array' => 'Le champ :attribute est invalide.',
    'image' => 'Le fichier :attribute doit être une image.',
    'file' => 'Le champ :attribute doit être un fichier.',
    'mimes' => 'Le fichier :attribute doit être de type : :values.',
    'regex' => 'Le format du champ :attribute est invalide.',
    'unique' => 'Cette valeur existe déjà pour :attribute.',
    'max' => ['string' => 'Le champ :attribute ne doit pas dépasser :max caractères.', 'file' => 'Le fichier :attribute ne doit pas dépasser :max Ko.', 'numeric' => ':attribute ne doit pas dépasser :max.', 'array' => ':attribute ne doit pas avoir plus de :max éléments.'],
    'min' => ['string' => 'Le champ :attribute doit contenir au moins :min caractères.', 'file' => 'Le fichier :attribute doit faire au moins :min Ko.', 'numeric' => ':attribute doit être au moins :min.', 'array' => ':attribute doit avoir au moins :min éléments.'],
    'uploaded' => 'Le téléversement de :attribute a échoué (fichier trop volumineux ?).',
    'attributes' => ['name' => 'nom', 'email' => 'e-mail', 'subject' => 'objet', 'message' => 'message', 'password' => 'mot de passe', 'image' => 'image', 'period' => 'période', 'country' => 'pays', 'section_key' => 'clé du bloc', 'sort_order' => 'ordre', 'video_file' => 'fichier vidéo', 'logo' => 'logo'],
];
