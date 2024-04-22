let selector = document.getElementById('posts__selector');

console.log('selector', selector)

$(document).ready(function () {
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
    $('#posts__selector').on('change', function () {
        var selectedValues = $(this).val();

        function fetchWithCsrf(url, options) {
            const csrfToken = getCsrfToken();
            options.headers = {
                ...options.headers,
                'X-CSRF-TOKEN': csrfToken
            };
            return fetch(url, options);
        }

        // Пример использования
        fetchWithCsrf('/posts', {
            method: 'POST',
            body: JSON.stringify({value:selectedValues}),
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        })
            .then(response => response.text())
            .then(result => {
                console.log(result);
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });

        // $.ajax({
        //     type: 'POST',
        //     url: '/posts/search',
        //     contentType: 'application/json',
        //     dataType: 'json',
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     data: JSON.stringify({ selectedValues: selectedValues }),
        //     success: function (response) {
        //         console.log('Успешно отправлено на сервер.');
        //         // Дополнительные действия после успешной отправки на сервер
        //     },
        //     error: function (xhr, status, error) {
        //         console.error('Ошибка при отправке на сервер:', error);
        //         // Дополнительные действия в случае ошибки
        //     }
        // });
    });
});
