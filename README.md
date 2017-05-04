# README #

This is the CLI command of PHP, with additional coloring the message for the CLI output

### What is this repository for? ###

* Manipulate all `.html` files inside folder and subfolders
* For all files in Widget Co, modify each outbound link to `http://acme.test/*` and add the http parameter `partner` with the value of `widget co`


### How do I get set up? ###

* Clone this repo
* Add all html files to manipulate in `source` folder
* Example usage :
```
php processor.php --src /folder1/root --dst /folder2/root
```