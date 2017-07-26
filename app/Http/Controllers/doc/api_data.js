define({ "api": [
  {
    "group": "About",
    "type": "get",
    "url": "/about/events",
    "title": "大事记列表",
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n \"events_list\": [\n  {\n    \"id\": 4,\n    \"title\": \"北大金秋正式成立3\",\n    \"time_node\": \"2017-06-23 13:34:38\",\n    \"avatar\": \"URL\"\n  },\n  {\n    \"id\": 3,\n    \"title\": \"北大金秋正式成立2\",\n    \"time_node\": \"2017-06-23 13:31:40\",\n    \"avatar\": \"URL\"\n  },\n  {\n    \"id\": 2,\n    \"title\": \"北大金秋正式成立1\",\n    \"time_node\": \"2017-06-23 13:31:38\",\n    \"avatar\": \"URL\"\n  },\n  {\n    \"id\": 1,\n    \"title\": \"北大金秋正式成立\",\n    \"time_node\": \"2017-06-23 13:29:38\",\n    \"avatar\": \"URL\"\n  }\n ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./UserController.php",
    "groupTitle": "About",
    "name": "GetAboutEvents"
  },
  {
    "type": "post",
    "url": "/api/v1/user/signup",
    "title": "User register",
    "name": "signup",
    "description": "<p>register users</p>",
    "success": {
      "fields": {
        "Reponse 200": [
          {
            "group": "Reponse 200",
            "type": "number",
            "optional": false,
            "field": "code",
            "description": "<p>200</p>"
          },
          {
            "group": "Reponse 200",
            "type": "json",
            "optional": true,
            "field": "data",
            "defaultValue": "\"\"",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Response 200 Example",
          "content": "HTTP/1.1 200 OK\n{\n    \"code\": 200,\n    \"data\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "group": "G__code_laravel_test_app_Http_Controllers_UserController_php",
    "groupTitle": "G__code_laravel_test_app_Http_Controllers_UserController_php"
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./doc/main.js",
    "group": "G__code_laravel_test_app_Http_Controllers_doc_main_js",
    "groupTitle": "G__code_laravel_test_app_Http_Controllers_doc_main_js",
    "name": ""
  }
] });
