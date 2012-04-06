# hCard ShortCode
hCard ShortCode translates vCard to hCard and let's you display them using a simple [hcard] shortcode.

## how it works:
1. install the plugin
2. upload the vCards in the Media section
3. choose a lowercase no-space Title for the vCards
4. use the [hcard vcard='vcard title' only='comma separated vcard fields to display'] shortcode 
5. style the resulting hCard using CSS

## which fields are supported?
hCard ShortCode supports the following vCard fields:

* `N` given name and family name;
* one `EMAIL` optionally use `TYPE` to specify a label to use in the &lt;a&gt; link;
* one `TITLE`
* one `ORG`
* one `PHOTO` with `TYPE=URL`
* one or more `URL`, optionally use `TYPE` to specify a label to use in the &lt;a&gt; link;
* one `ADR`

## can you give me a sample vCard?
Sure, here it is:

    BEGIN:VCARD
    N:Paganotti;Sandro;;;
    EMAIL;TYPE=Email:sandro.paganotti@compartoweb.com
    TITLE:Software Architect
    ORG:Comparto Web
    PHOTO;VALUE=URL;TYPE=PNG:/wp-content/uploads/2012/04/official_me-43x43.png
    URL;TYPE=Twitter:https://twitter.com/#!/sandropaganotti
    URL;TYPE=LinkedIn:http://www.linkedin.com/in/sandropaganotti
    ADR;INTL;PARCEL;WORK:;;Via Cipro 66;Brescia;Brescia;25100;Italia
    END:VCARD

## how can use the 'only' option?
the `only` option can be used to limit the fields printed on the hCard, you
can declare a comma-separated list of the fields you want to be displayed, here's the
keyword:

* `name` for given name and family name
* `email` for the `EMAIL`
* `org:title` for the `TITLE` 
* `org:name` for the `ORG`
* `url` for the `URL`s
* `photo` for the `PHOTO`
* `location` for thr `ADR`

## an example:
Here's a shortcode example:

    [hcard vcard="sandro_paganotti" only="name,email,org:title,photo,url"]

## kudos and acknowledgements:
This plugin uses the cool Contact_Vcard_Parse class from Paul M. Jones <pjones@ciaweb.net> and a modified version of the phpMicroformats class from Tobias Kluge (enarion.net). Thank you!