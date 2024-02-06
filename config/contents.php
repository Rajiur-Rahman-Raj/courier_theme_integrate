<?php
return [
	'feature' => [
		'field_name' => [
			'title' => 'text',
			'short_description' => 'textarea',
			'image' => 'file',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'short_description.*' => 'required|min:2|max:2000',
			'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		],
		'size' => [
            'image' => '95x105'
        ]
	],

	'services' => [
		'field_name' => [
			'title' => 'text',
			'short_description' => 'textarea',
			'image' => 'file',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'short_description.*' => 'required|min:2|max:2000',
			'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		],
	],

	'why-choose-us' => [
		'field_name' => [
			'title' => 'text',
			'short_description' => 'textarea',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'short_description.*' => 'required|min:2|max:2000',
		],
	],

	'testimonial' => [
		'field_name' => [
			'name' => 'text',
			'designation' => 'text',
			'rating' => 'number',
			'feedback' => 'textarea',
			'image' => 'file',
		],
		'validation' => [
			'name.*' => 'required|min:2|max:100',
			'designation.*' => 'required|min:2|max:100',
			'rating.*' => 'required|numeric|min:1|max:5|not_in:0',
			'short_description.*' => 'required|min:2|max:2000',
			'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		],
		'size' => [
			'image' => '427x427'
		]
	],

	'how-it-work' => [
		'field_name' => [
			'title' => 'text',
			'short_description' => 'textarea',
			'image' => 'file',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'short_description.*' => 'required|min:2|max:2000',
			'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		],
	],

	'faq' => [
		'field_name' => [
			'title' => 'text',
			'short_description' => 'textarea',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'short_description.*' => 'required|min:2|max:2000',
		],
	],

	'social-links' => [
		'field_name' => [
			'social_icon' => 'icon',
			'social_link' => 'url',
		],
		'validation' => [
			'social_icon.*' => 'required',
			'social_link.*' => 'required|url',
		],
	],
	'extra-pages' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:100000'
        ]
    ],

	'message' => [
		'required' => 'This field is required.',
		'min' => 'This field must be at least :min characters.',
		'max' => 'This field may not be greater than :max characters.',
		'image' => 'This field must be image.',
		'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
	],

	'content_media' => [
		'image' => 'file',
		'thumbnail' => 'file',
		'youtube_link' => 'url',
		'social_icon' => 'icon',
		'social_link' => 'url',
		'button_link' => 'url',
	]
];
