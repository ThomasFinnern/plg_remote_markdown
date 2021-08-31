# plg_remote_markdown

Joomla plugin displaying remote linked markdown files in article
This Joomla! plugin will replace a given link to a *.md in the 
form <strong>{remotemarkdown:http://.../...md}</strong> to an 
html out put in an article.

The idea is to create user documentation in markdown on github and show it on a joomla web site.

The plugin uses the parsedown project https://github.com/erusev/parsedown 
to convert the *.md file (LICENSE file see folder)

**State: Basic working example** (No fringes)


## Usage:
* Install plugin 
* Activate plugin 
* Add following to an article "{remotemarkdown:\<http: file-url\>}

## Examples:
```
{remotemarkdown:http://127.0.0.1/readme.md}
{remotemarkdown:https://raw.githubusercontent.com/RSGallery2/RSGallery2_Project/master/Documentation/Maintenance/Maint.SlideshowConfig.md}
```
## Attention: 

It won't display github flavoured markdown.

Following url/file can't be displayed proper

https://raw.githubusercontent.com/RSGallery2/RSGallery2_Project/master/Documentation/RSGallery2_documentation_J3.x.md

## Possible Future features:
* Caching of read files
* Parameters also in article reference

