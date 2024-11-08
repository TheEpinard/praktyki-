document.addEventListener('DOMContentLoaded', () => { 
    const form = document.querySelector('form');
    const resultDiv = document.getElementById('result');
    const loader = document.getElementById('loader');

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); 
        resultDiv.innerHTML = '<p>Przetwarzanie...</p>';
        loader.style.display = 'block';

        const formData = new FormData(form);

        try {
            const response = await fetch('process.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Błąd sieci: ' + response.statusText);

            const data = await response.json();

            // Ukryj loader
            loader.style.display = 'none';

            // Obsługuje odpowiedź JSON
            if (data.error) {
                resultDiv.innerHTML = `<p style="color:red;">${data.error}</p>`;
                return;
            }

            // Wyświetlanie wyników
            console.log(data);
            const userNumbers = data.userNumbers.join(', ');
            const randomNumbers = data.randomNumbers.join(', ');
            const matches = data.matchingNumbers.join(', ');
            const prize = data.prize;

            resultDiv.innerHTML = `
                <h3>Wyniki Losowania</h3>
                <p><strong>Twoje liczby:</strong> ${userNumbers}</p>
                <p><strong>Wylosowane liczby:</strong> ${randomNumbers}</p>
                <p><strong>Trafione liczby:</strong> ${matches}</p>
                <p><strong>Liczba trafień:</strong> ${data.numberOfMatches}</p>
                <p><strong>Wygrana:</strong> ${prize}</p>
            `;
        } catch (error) {
            loader.style.display = 'none';
            console.error('Błąd:', error);
            resultDiv.innerHTML = '<p style="color:red;">Wystąpił błąd. Spróbuj ponownie później.</p>';
        }
    });
});