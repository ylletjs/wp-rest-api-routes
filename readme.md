# Routes endpoint

[![Build Status](https://travis-ci.org/ylletjs/wp-rest-api-routes.svg?branch=master)](https://travis-ci.org/ylletjs/wp-rest-api-routes)

Exposing rewrite rules through the REST API.

New endpoint:

```
/wp-json/wp/v2/routes
```

Example response:

```json
[
  {
	  "path": "^wp-json\/?$",
	  "type": "unkown"
  },
  {
	  "path": "^wp-json\/(.*)?",
	  "type": "unkown"
  },
  {
	  "path": "^index.php\/wp-json\/?$",
	  "type": "unkown"
  },
  {
	  "path": "^index.php\/wp-json\/(.*)?",
	  "type": "unkown"
  },
  {
	  "path": "blog\/category\/(.+?)\/feed\/(feed|rdf|rss|rss2|atom)\/?$",
	  "type": "category"
  },
  {
	  "path": "blog\/category\/(.+?)\/(feed|rdf|rss|rss2|atom)\/?$",
	  "type": "category"
  }
]
```

## Installation

```
composer require ylletjs/wp-rest-api-routes
```

## License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
