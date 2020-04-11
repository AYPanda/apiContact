$(document).ready(function () {
    var sourceIds = {
        "1": "Первый источник",
        "2": "Второй источник"
    };
    $('#table').hide();

    for (var id in sourceIds) {
        $("#source_id").append('<option value="'+id+'">'+ sourceIds[id] +'</option>');
    }

    $('#search').click(function () {
        let phone = $('#phone-search').val();
        $.ajax({
            url: '/api/contacts',
            type: 'get',
            data: {phone},
            success: function (data) {
                let table = '';
                for (let i = 0; i < data.length; i++) {
                    table += `<tr>
                                <td>${data[i].id}</td>
                                <td>${data[i].name}</td>
                                <td>${data[i].phone}</td>
                                <td>${data[i].email}</td>
                                <td>${sourceIds[data[i].source_id]}</td>
                            </tr>`
                }
                $('#body-table').html(table);
                $('#table').show();
            }
        });
    });
    $('#create').click(function () {
        let name = $('#name').val();
        let phone = $('#phone').val();
        let email = $('#email').val();
        let source_id = $('#source_id').val();
        $.ajax({
            url: '/api/contacts',
            type: 'post',
            dataType: "json",
            data: JSON.stringify({ source_id, items: [{ name, phone, email}]}),
            success: function (data) {
                const countRows = parseInt(data);

                if (!isNaN(countRows))
                {
                    $('#name').val("");
                    $('#phone').val("");
                    $('#email').val("");
                    $('#source_id').val("-1");

                    $('#message').html(`Количество добавленных строк: ${countRows}`);
                }
                else
                {
                    $('#message').html(`Произошла ошибка ${data}`);
                }

            }
        });
    })
});
