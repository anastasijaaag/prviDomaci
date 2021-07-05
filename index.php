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
    <title>Katedre</title>
</head>

<body>
    <?php
        include "header.php";
    ?>

    <div class="katedre">

        <div id="katedreKontejner">


        </div>
        <input type="text" hidden="true" id="trenutniId" />
        <style type="text/css">
            .red {
                margin-top: 10%;
            }
        </style>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Izmeni katedru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formaZaIzmenuKatedre">
                        <div class="form-group">
                            <label for="naziv-name" class="col-form-label">naziv</label>
                            <input type="text" class="form-control" id="naziv-name">
                        </div>
                        <div class="form-group">
                            <label for="sef-name" class="col-form-label">Sef katedre</label>
                            <select class="form-control" id="sef-name"></select>
                        </div>
                        <div class="form-group">
                            <label for="opis-text" class="col-form-label">opis:</label>
                            <textarea class="form-control" id="opis-text"></textarea>
                        </div>
                    </form>

                    <div id="listaClanova">
                        <h5>Profesori</h5>
                        <select size="6" class="form-control" id="profesoriNaKatedri">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                    <button type="button" class="btn btn-primary" id="izmena">Izmeni</button>
                </div>

            </div>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            napuniKatedre();
            $('#exampleModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget) // Button that triggered the modal
                let naziv = button.data('naziv');
                let opis = button.data('opis')
                let sef = button.data('sef')
                let id = button.data('id');
                $("#trenutniId").val(id);
                // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

                let modal = $(this);
                modal.find('.modal-title').text(naziv)
                modal.find('#naziv-name').val(naziv);
                modal.find("#opis-text").val(opis);
                $.getJSON("vratiProfesoreSaKatedre.php", { katedra: $("#trenutniId").val() }, function (data) {

                    if (data.status !== "ok") {
                        alert(data.status);
                        return;
                    }
                    $("#sef-name").html("<option value='0'>Nema sefa</option>");
                    for (let profesor of data.profesori) {
                        $("#sef-name").append(`<option value='${profesor.id}'>${profesor.ime} ${profesor.prezime}</option>`);
                    }
                    $("#profesoriNaKatedri").html("");
                    for (let profesor of data.profesori) {
                        $("#profesoriNaKatedri").append(`<option value='${profesor.id}'>${profesor.ime} ${profesor.prezime}</option>`);
                    }
                    
                    $("#sef-name").val(sef);
                    if (data.status !== "ok") {
                        alert(data.status);
                        return;
                    }

                })





            });
            $("#izmena").click(function () {
                let naziv = $("#naziv-name").val();
                let sef = $("#sef-name").val();
                let opis = $("#opis-text").val();
                let id = $("#trenutniId").val();
                $.post("katedre.php", { akcija: "izmeni", naziv: naziv, sef: sef, opis: opis, id: id }, function (data) {
                    alert(data);
                    napuniKatedre();
                })
            })
        })

        function napuniKatedre() {
            $.getJSON("katedre.php", { akcija: "vrati detaljno" }, function (data) {
                napuni(data);
            })

        }
        function napuni(data) {
            if (data.status !== "ok") {
                alert(data);
                return;
            }
            let red = 1;
            let kolona = 0;
            $("#katedreKontejner").html(`<div class="row red" id="Row-${red}" ></div>`);
            for (let katedra of data.katedre) {
                if (kolona > 2) {
                    kolona = 0;
                    red++;
                    $("#katedreKontejner").append(`<div class="row red" id="Row-${red}" ></div>`);
                }
                $(`#Row-${red}`).append(`<div class="col-3 katedraDiv">
                 <h4 >${katedra.naziv}</h4>
                <br>
                <p >Sef katedre: ${(katedra.sef) ? katedra.sefIme + " " + katedra.sefPrezime : "nema"}</p>
                <p>Opis: ${katedra.opis}</p>
                <br>
                <button class="btn btn-info" id="izmeni" data-toggle="modal"
                     data-target="#exampleModal" data-naziv="${katedra.naziv}" data-id="${katedra.id}" data-opis="${katedra.opis}" data-sef="${katedra.sef || 0}">Izmeni</button>
                    
                     <button class="btn btn-danger" id="${katedra.id}obrisi">Obrisi</button>
            </div>`);
                $(`#${katedra.id}obrisi`).click(function (e) {
                    e.preventDefault();
                    obrisiKatedru(katedra);
                })
                kolona++;
            }
        }
        function obrisiKatedru(katedra) {
            $.post("katedre.php", { akcija: "obrisi", id: katedra.id }, function (data) {
                console.log(data);
                /* if (data !== "ok") {
                    alert(data);
                } */
                napuniKatedre();
            })
        }
    </script>
</body>

</html>