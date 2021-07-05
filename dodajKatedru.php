<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel=" stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <?php
        include "header.php";
    ?>
    <div class="formaZaDodavanje">
        <h1>Dodaj katedru</h1>
        <form class="mt-5">
            <div class="form-group">
                <input type="text" class="form-control" id="noviNaziv" placeholder="Naziv">
            </div>
            <div class="form-group">
                <select class="form-control" id="sef">

                </select>
            </div>
            <div class="form-group">
                <textarea class="form-control" id="opis" rows="3" placeholder="Opis"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" id="dodaj">Dodaj </button>
            </div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            napuniProfesore();
            $("#dodaj").click(function (e) {
                e.preventDefault();
                let naziv = $("#noviNaziv").val();
                let sef = $("#sef").val();
                let opis = $("#opis").val();
                $.post("katedre.php", { akcija: "dodaj", naziv: naziv, sef: sef, opis: opis }, function (data) {
                    alert(data);
                })
            })
        })
        function napuniProfesore() {
            $.getJSON("vratiProfesoreBezKatedre.php", function (data) {

                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                $("#sef").html("<option value='0'>Sef katedre</option>");
                for (let profesor of data.profesori) {
                    $("#sef").append(`<option value='${profesor.id}'>${profesor.ime} ${profesor.prezime}</option>`);
                }
                $("#sef").val('0');
            })
        }
    </script>
</body>

</html>