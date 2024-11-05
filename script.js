document.addEventListener('DOMContentLoaded', function() { 
    const form = document.querySelector('form');
    const resultDiv = document.getElementById('result');
    const loader = document.getElementById('loader'); // Element loadera

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Zapobiegaj domyślnemu wysłaniu formularza
        
        // Wyświetlanie wiadomości o przetwarzaniu oraz uruchomienie loadera
        resultDiv.innerHTML = '<p>Przetwarzanie...</p>';
        loader.style.display = 'block';

        const formData = new FormData(form);

        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }
        

        fetch('process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none'; // Ukryj loader po otrzymaniu odpowiedzi

            console.log('Odpowiedź serwera:', data); // Dodaj to dla debugowania

            if (data.error) {
                resultDiv.innerHTML = `<p style="color:red;">${data.error}</p>`;
                return;
            }

            // Wygenerowanie treści wyników w formacie HTML
            let output = `
                <p>Twoje liczby: ${data.userNumbers.join(', ')}</p>
                <p>Wylosowane liczby: ${data.randomNumbers.join(', ')}</p>
                <p>Trafiłeś ${data.numberOfMatches} liczb(y): ${data.matches.join(', ')}</p>
                <p>Twoja wygrana: ${data.prize}</p>
            `;
            resultDiv.innerHTML = output;
        })
        .catch(error => {
            loader.style.display = 'none'; // Ukryj loader w przypadku błędu
            console.error('Błąd:', error);
            resultDiv.innerHTML = '<p style="color:red;">Wystąpił błąd. Spróbuj ponownie później.</p>';
        });
    });
});
