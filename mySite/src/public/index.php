<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hello world page</title>

</head>
<body>



    <style>
        body {
            background: -webkit-linear-gradient(90deg, #f995bd,#51c5ec);                         
            background: linear-gradient(90deg, #f995bd,#51c5ec);   
            font-family: 'Trebuchet MS', Helvetica, sans-serif;  
            font-size: 20px;
        }
        .update {
            display: inline-block;
            height: 15px;
            width: 15px;
        }
        .update:hover {
            cursor: pointer;
        }

        .delete {
            display: inline-block;
            height: 15px;
            width: 15px;
        }
        .delete:hover {
            cursor: pointer;
        }

        input {
            border-radius: 5px;
            outline: none;
            text-decoration: none;
            border: none;
            background: white;
            margin: 0;
            font-family: 'Trebuchet MS', Helvetica, sans-serif;
            padding: 4px;
            font-size: 17px;
        }
        table input {
            width: 300px;
        }
       
        #owner_block {
            display: inline-block;
            border-radius: 5px;
            outline: none;
            text-decoration: none;
            border: none;
            background: white;
            margin: 0;
            font-family: 'Trebuchet MS', Helvetica, sans-serif;
            padding: 4px;
            font-size: 17px;
            width: 300px;
        }

        #add_record_btn {
            width: 308px;
        }
        table {
            margin-top
        }

        .btn {
            border-width: 3px;
            border-color: black;
            border-style: solid;
        }
        .btn:hover {
            cursor: pointer;
        }
        
    </style>



    <?php
    // Необходимые импорты
    include_once "api/config/database.php";
    include_once "api/objects/users.php";
    include_once "api/objects/schedule.php";
    include_once "api/users/authorized_user.php";
    // include_once "/api/schedule/get_user_schedule.php";

    // require __DIR__ . '/api/schedule/get_user_schedule.php';

    // Подключение к БД
    $database = new Database();
    $db = $database->getConnection();
    ?>

    <script>
        async function Request(url, method = 'GET', bodyObj = null, token = null) {
        
        let result = null;
        let obj = {};
    
        obj['method'] = method;
        
        obj['headers'] = {};
        obj['headers']['Content-type'] = 'application/json';
        if (token) obj['headers']['Authorization'] = `Bearer ${token}`;
            
        if (bodyObj) obj['body'] = JSON.stringify(bodyObj);
        
        await fetch(url, obj).then(async (response) => {
            console.log(response)
            if (response.ok)
                await response.json().then(data => {
                    console.log(data);
                    result = data;
                })
            else 
                await response.text().then(data => {
                    console.log(data);
                    result = data;
                })
        });
    
        return result;
    }
    async function get_user_schedule() {
        let tmp = await Request('/api/schedule/get_user_schedule.php');
        //console.log(typeof tmp)
        if(typeof tmp != 'string') {
            let a = `<table>
                        <tr>
                            <td><input type="text" id="name" name="name" required placeholder='name'></td>
                            <td><input type="text" id="description" name="description" required placeholder='description'></td>
                            <td><input type="text" id="deadline" name="deadline" required placeholder='deadline'></td>
                            <td><input class='btn' id='add_record_btn' type="submit" value="Add record" onClick="(async()=> {
                                let a = await Request('api/schedule/create_user_schedule.php', 'POST', {
                                    name: document.getElementsByTagName('tr')[0].getElementsByTagName('input')[0].value,
                                    description: document.getElementsByTagName('tr')[0].getElementsByTagName('input')[1].value,
                                    deadline: document.getElementsByTagName('tr')[0].getElementsByTagName('input')[2].value
                                });
                                location.reload();
                                alert(JSON.parse(a).message)
                            })()"></td>
                        </tr>`;
            for(let i = 0; i < tmp.length; i++) {
                a += `
                    <tr>

                        <td><input value='${tmp[i]['name']}'></td>
                        <td><input value='${tmp[i]['description']}'></td>
                        <td><span id='owner_block'>${tmp[i]['owner']}</span></td>
                        <td><input value='${tmp[i]['deadline']}'></td>
                        <td onClick="(async ()=> {
                            let a = await Request('/api/schedule/update_schedule.php?id=${tmp[i]['id']}', 'PUT', {
                                name: document.getElementsByTagName('tr')[${i + 1}].getElementsByTagName('input')[0].value,
                                description: document.getElementsByTagName('tr')[${i + 1}].getElementsByTagName('input')[1].value,
                                deadline: document.getElementsByTagName('tr')[${i + 1}].getElementsByTagName('input')[2].value
                            });
                            location.reload();
                            alert(JSON.parse(a).message)
                        })()"><svg class='update' xmlns="http://www.w3.org/2000/svg" height="15" viewBox="0 -960 960 960" width="15"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg></td>
                        <td onClick="(async ()=> {
                            let a = await Request('/api/schedule/delete_schedule.php?id=${tmp[i]['id']}', 'DELETE');
                            location.reload();
                            alert(JSON.parse(a).message)
                        })()"><svg class='delete' xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></td>
                    </tr>
                `
            }
            a += '</table>'
            //console.log(a)
            let body = document.getElementsByTagName('body')[0]
            body.innerHTML += a
        }
    }
    get_user_schedule()

    </script>

    <h1>Расписание</h1>
    <label for="login">Login:</label>
    <input type="text" id="login" name="login" required><br><br>
    <label for="password">Password:</label>
    <input type="text" id="password" name="password" required>
    <input type="text" id="password" name="New password" placeholder='New password' required>
    <svg xmlns="http://www.w3.org/2000/svg" height="1" viewBox="0 -960 960 960" width="1" class='update' onClick="(async ()=> {
        let a = await Request('/api/users/change_user.php', 'PUT', {
            password: document.getElementsByTagName('input')[2].value
        });
        location.reload();
        alert(JSON.parse(a).message)
    })()"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
    </svg>
    <br><br>
    <input class='btn' type="submit" value="Login" onClick="(async ()=> {
        let a = await Request('/api/users/log_in.php', 'POST', {
            login: document.getElementsByTagName('input')[0].value,
            password: document.getElementsByTagName('input')[1].value
        });
        location.reload()
        alert(JSON.parse(a).message)
    })()">
    <input class='btn' type="submit" value="Logout" onClick="(async ()=> {
        let a = await Request('/api/users/log_out.php', 'UPDATE');
        location.reload();
        alert(JSON.parse(a).message)
    })()">
    <input class='btn' type="submit" value="Register" onClick="(async ()=> {
        let a = await Request('/api/users/create_user.php', 'POST', {
            login: document.getElementsByTagName('input')[0].value,
            password: document.getElementsByTagName('input')[1].value
        });
        location.reload()
        alert(JSON.parse(a).message)
    })()">
    <input class='btn' type="submit" value="Delete user" onClick="(async ()=> {
        let a = await Request('/api/users/delete_user.php', 'DELETE');
        location.reload()
        alert(JSON.parse(a).message)
    })()"><br>
        
    </body>
</html>