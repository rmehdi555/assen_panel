<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | You can add as many as you want of profiles to use it in your application.
    |
    */

    'profiles' => [

        'default' => [
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount ',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen  | fontselect | lineheight',
            'upload_directory' => null,
            'custom_configs' => [
                'font_formats' => 'IRANSans=iran-sans; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Tahoma=tahoma,arial,helvetica,sans-serif;',
                'fontsize_formats' => '8pt 10pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 48pt 72pt',
                'content_style' => '
                                        h1 { font-size: 24pt !important; }
                                        h2 { font-size: 22pt !important; }
                                        h3 { font-size: 20pt !important; }
                                        h4 { font-size: 16pt !important; }
                                        h5 { font-size: 14pt !important; }
                                        h6 { font-size: 12pt !important; }
                                        p + h1, p + h2, p + h3, p + h4, p + h5, p + h6 { margin-top: 20px; }
                                    '
            ],
        ],

        'simple' => [
            'plugins' => 'autoresize directionality emoticons link wordcount',
            'toolbar' => 'removeformat | bold italic | rtl ltr | link emoticons',
            'upload_directory' => null,
        ],

        'template' => [
            'plugins' => 'autoresize template',
            'toolbar' => 'template',
            'upload_directory' => null,
        ],
        /*
        |--------------------------------------------------------------------------
        | Custom Configs
        |--------------------------------------------------------------------------
        |
        | If you want to add custom configurations to directly tinymce
        | You can use custom_configs key as an array
        |
        */

        /*
          'default' => [
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
            'custom_configs' => [
                'allow_html_in_named_anchor' => true,
                'link_default_target' => '_blank',
                'codesample_global_prismjs' => true,
                'image_advtab' => true,
                'image_class_list' => [
                  [
                    'title' => 'None',
                    'value' => '',
                  ],
                  [
                    'title' => 'Fluid',
                    'value' => 'img-fluid',
                  ],
              ],
            ]
        ],
        */

    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | You can add as many as you want of templates to use it in your application.
    |
    | https://www.tiny.cloud/docs/plugins/opensource/template/#templates
    |
    | ex: TinyEditor::make('content')->profiles('template')->template('example')
    */

    'templates' => [

        'example' => [
            // content
            ['title' => 'Some title 1', 'description' => 'Some desc 1', 'content' => 'My content'],
            // url
            ['title' => 'Some title 2', 'description' => 'Some desc 2', 'url' => 'http://localhost'],
        ],

    ],
];
