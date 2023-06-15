<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="google" content="notranslate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Configuratore Locker</title>
</head>
<body>
<br>
<div class="container">
<h1>Configuratore Locker</h1>
<label for="column-count-input">Column Count:</label>
<input type="number" id="column-count-input" min="1" max="10" value="0">

<div id="table-container"></div>

<template id="table-template">
  <table class="table locker" border="1" style="margin-top: 20px;">
    <thead>
      <tr>
        <th scope="col">1</th>
        <th scope="col">2</th>
        <th scope="col">3</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Column 1</td>
        <td>Column 2</td>
        <td>Column 3</td>
      </tr>
      <tr>
        <td>Column 1</td>
        <td>Column 2</td>
        <td>Column 3</td>
      </tr>
      <tr>
        <td>Column 1</td>
        <td>Column 2</td>
        <td>Column 3</td>
      </tr>
    </tbody>
  </table>
</template>

</div>
<script src="dynamicTable.js"></script>
</body>
</html>