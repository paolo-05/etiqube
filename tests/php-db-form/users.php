<!DOCTYPE HTML>
<html>
<head>
    <meta name="google" content="notranslate">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>User Management - Update User</title>
</head>
<body>
<br>
<div class="container">
    <h2>Update User</h2>
    <table class="table">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Created At</th>
            <th scope="col">Last Modified</th>
            <th scope="col">Action</th>
        </tr>
        <?php

        $url = 'http://127.0.0.1:8080/php-db-form/api/db-interactions.php?action=getAllUsers';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response from the API endpoint
        if ($response === false) {
            echo "Error: " . curl_error($ch);
            return null;
        } else {
            $users = json_decode($response, true);
        }

        foreach ($users['data'] as $u) {
            echo "<tr>";
            echo "<th scope='row'>{$u['id']}</th>";
            echo "<td>{$u['name']}</td>";
            echo "<td>{$u['email']}</td>";
            echo "<td>{$u['created_at']}</td>";
            echo "<td>{$u['last_modified']}</td>";
            echo "<td>
            <button type='button' class='btn btn-primary' onclick= document.location.href='form.php?id={$u['id']}' >Update</button>
            <button type='button' class='btn btn-danger' onclick=deleteUser({$u['id']}) >Delete</button>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <button type="button" class="btn btn-primary" onclick="document.location.href = '/php-db-form/'">Home</button>
    <button type="button" class="btn btn-primary" onclick="document.location.href = '/php-db-form/form.php'">Insert a new user</button>
</div>
<script>
    function deleteUser(id) {
        var confirmation = confirm("Are you sure you want to delete this user?");

        if (confirmation) {
            var deleteUrl = 'http://127.0.0.1:8080/php-db-form/api/db-interactions.php?action=deleteUser&id=' + id;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", deleteUrl, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send();
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
