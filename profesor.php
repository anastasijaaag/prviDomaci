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
    <title>Profesori</title>
</head>

<body>
    <?php
    include "header.php";
    ?>
    <div id="omotac">
        <div id="profesori">
            <table class="table display">
                <tr>
                    <th scope="col">Ime</th>
                    <th scope="col">Prezime</th>
                    <th scope="col">Zvanje</th>
                    <th scope="col">Katedra</th>
                </tr>
                <tbody id="profesoriBody">

                </tbody>
            </table>

        </div>
        <div id="formaZaNovogProfesora">
            <form class="mt-5">
                <div class="form-group">
                    <input type="text" class="form-control" id="ime" placeholder="Ime">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" type="text" id="prezime" placeholder="Prezime">
                </div>
                <div class="form-group">
                    <select class="form-control" id="zvanje"></select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="katedra"></select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" id="dodajProfesora">Dodaj profesora</button>
                    <button class="btn btn-primary" id="izmeniProfesora" hidden="true">Izmeni profesora</button>
                    <button class="btn btn-primary" id="vratiSe" hidden="true">Vrati nazad</button>
                </div>
                <input type="text" hidden="true" id="idProfesora" />
            </form>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            napuniZvanja();
            napuniTabelu();
            napuniKatedre();
            $("#vratiSe").click(function (e) {
                e.preventDefault();
                $("#dodajProfesora").attr("hidden", false);
                $("#izmeniProfesora").attr("hidden", true);
                $("#vratiSe").attr("hidden", true);
                $("#ime").val("");
                $("#prezime").val("");
                $("#zvanje").val("");
                $("#katedra").val("");
                $("#idGlumca").val("");
            })
            $("#izmeniProfesora").click(function (e) {
                e.preventDefault();
                let ime = $("#ime").val();
                let prezime = $("#prezime").val();
                let zvanje = $("#zvanje").val();
                let katedra = $("#katedra").val();
                let id = $("#idProfesora").val();
                $.post("profesori.php", { akcija: "izmeni", ime: ime, prezime: prezime, zvanje: zvanje, katedra: katedra, id: id }, function (data) {
                    if (data !== "ok")
                        alert(data);
                    napuniTabelu();
                })
            })
            $("#dodajProfesora").click(function (e) {
                e.preventDefault();
                let ime = $("#ime").val();
                let prezime = $("#prezime").val();
                let zvanje = $("#zvanje").val();
                let katedra = $("#katedra").val();
                $.post("profesori.php", { akcija: "dodaj", ime: ime, prezime: prezime, zvanje: zvanje, katedra: katedra }, function (data) {
                    if (data !== "ok")
                        alert(data);
                    napuniTabelu();
                })
            })
        
        })
        function napuniZvanja() {
            $.getJSON("zvanja.php", function (data) {
                console.log(data);
                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                $("#zvanje").html("<option value='0'>Nema</option>");
                for (let zvanje of data.zvanja) {
                    $("#zvanje").append(`
                        <option value="${zvanje.id}">${zvanje.naziv}</option>
                    `)
                }
            });
        }
        function napuniKatedre() {
            $.getJSON("katedre.php", { akcija: "naziv" }, function (data) {
                console.log(data);
                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                $("#katedra").html("<option value='0'>Nema</option>");
                for (let katedra of data.katedre) {
                    $("#katedra").append(`
                        <option value="${katedra.id}">${katedra.naziv}</option>
                    `)
                }
            });
        }
        function napuniTabelu() {
            $.getJSON("vratiProfesore.php", function (data) {
                if (data.status !== "ok") {
                    alert(data.status);
                    return;
                }
                $("#profesoriBody").html("");
                for (let profesor of data.profesori) {
                    $("#profesoriBody").append(`
                        <tr>
                            <td>${profesor.ime}</td>
                            <td>${profesor.prezime}</td>
                            <td>${profesor.nazivZvanja}</td>
                            <td>${profesor.nazivKatedre}</td>
                            <td> <button class="dugmeUnutarTabele" id=${profesor.id}-IzmeniProfesora >Izmeni</button> </td>
                            <td> <button class="dugmeUnutarTabele" id=${profesor.id}-ObrisiProfesora  >Obrisi</button> </td>
                        </tr>
                    `);

                    $(`#${profesor.id}-IzmeniProfesora`).click(function () {
                        napuniZaIzmenu(profesor);
                    })
                    $(`#${profesor.id}-ObrisiProfesora`).click(function () {
                        console.log("brisanje");
                        $.post("obrisiProfesora.php",{id:profesor.id}, function (data) {
                            if (data !== "ok") {
                                alert(data);
                            }
                            napuniTabelu();
                        })
                    })

                }

            })

        }
        function napuniZaIzmenu(profesor) {
            $("#dodajProfesora").attr("hidden", true);
            $("#izmeniProfesora").attr("hidden", false);
            $("#vratiSe").attr("hidden", false);
            $("#ime").val(profesor.ime);
            $("#prezime").val(profesor.prezime);
            $("#idProfesora").val(profesor.id);
            $("#katedra").val(profesor.katedra||'0');
            $("#zvanje").val(profesor.zvanje|| '0');
        }
    </script>
</body>

</html>