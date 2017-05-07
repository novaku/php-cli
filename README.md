# README #

This is the CLI command of PHP, with additional coloring the message for the CLI output

### What is this repository for? ###

* Manipulate all `.html` files inside folder and subfolders
* For all `.html` files in `source` folder, modify each outbound link to `http://acme.test/*` and add the http parameter `partner` with the value of `widget co`


### How do I get set up? ###

* Clone this repo
* Add all html files to manipulate in `source` folder
* Create folder `destination` and make it writable
* Run this scrip in PHP CLI mode, and do the following shell command :
```
php processor.php --src source --dst destination
```
>``--src : source folder``

>``--dst : destination folder``

* Result will be collected in `destination` folder with the same name and extension
