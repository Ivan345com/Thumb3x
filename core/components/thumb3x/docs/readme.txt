Thumb3x
--------------------
Author: Ivan Sergeevish K.
ivan@modxprofi.ru
--------------------

For MODx Revolution 3.x.

<img src="[[!Thumb3x? &input=`path/to/image.jpg` &options=`w=300&h=200`]]" alt="">

Fenom

{'!Thumb3x' | snippet : [
    'input' => 'path/to/image.jpg',
    'options' => [
        'w' => 300,
        'h' => 200
    ]
]}

{var $params = [
    'w' => 300,
    'h' => 300,
    'fit' => 'crop',
    'filt' => 'sepia',
    'q' => 75,
    'fm' => 'webp'
]}

<img src="{'!Thumb3x' | snippet : [
    'input' => 'path/to/avatar.jpg',
    'options' => $params
]}" alt="Avatar in sepia">

https://docs.ivan345.com/books/thumb3x/page/thumb3x-dokumentaciia
https://docthumb3x.ivan345.ru/en/
https://glide.thephpleague.com/
