# Code Formatter Tests

## JavaScript:

```
class TestJs{first_name="";last_name="";constructor(first_name,last_name){this.first_name=first_name
this.last_name=last_name}GenerateName(){return`${this.first_name} ${this.last_name}`}}const first_name_test_js="Jon";const last_name_test_js="Doe";const test_js=new TestJs(first_name_test_js,last_name_test_js);const full_name_test_js=test_js.GenerateName();console.log(full_name_test_js);
```

## TypeScript:

```
class TestTs{first_name="";last_name="";constructor(first_name,last_name){this.first_name=first_name;this.last_name=last_name;}GenerateName(){return`${this.first_name} ${this.last_name}`}}const first_name_test_ts="Jon";const last_name_test_ts="Doe";const test_ts=new TestTs(first_name_test_ts,last_name_test_ts);const full_name_test_ts=test_ts.GenerateName();console.log(full_name_test_ts);
```

## JSON:

```
{"first_name": "Jon","last_name": "Doe","address": {"country": "USA","state": "FL","city": "Miami","address": "Maimi Downtown"}}
```

## CSS:

```
body{width:100%}body>header{width:100%}
```

## LESS:

```
body{width:100%;header{width:100%;.logo{width:100%;max-width:250px;&:hover{text-decoration:underline;}}}}
```

## HTML:

```
<html><head><title>Title Goes Here</title><meta charset="UTF-8" /><link rel="stylesheet" href="styles.css" /><script src="scripts.js"></script></head><body><table><thead><tr><th>Head 1</th><th>Head 2</th><th>Head 3</th></tr></thead><tbody><tr><td>Body 1</td><td>Body 2</td><td>Body 3</td></tr></tbody></table><hr><ul><li><a href="link1"><img src="img1" alt=""></a></li><li><a href="link2"><img src="img2" alt=""></a></li></ul></body></html>
```
