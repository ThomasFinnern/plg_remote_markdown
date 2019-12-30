# plg_remote_markdown

-- branch b_test_namespace --

Joomla plugin displaying remote linked markdown files in article

The idea was to create user documentation in markdown on github and show it on the joomla web site.


**State: Basic working example** (No fringes)

Usage:
* Install plugin 
* Activate plugin 
* Add following to an article "{remotemarkdown:\<file-url\>}

Examples:
```
{remotemarkdown:http://127.0.0.1/readme.md}
```

Following url/file can't be displayed proper

https://raw.githubusercontent.com/RSGallery2/RSGallery2_Project/master/Documentation/RSGallery2_documentation_J3.x.md

## Possible Future features:
* Caching of read files
* Parameters also in article reference

