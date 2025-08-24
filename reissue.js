document.getElementById('reissueForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = {
            fullName: document.getElementById('fullName').value,
            dob: document.getElementById('dob').value,
            birthRegNum: document.getElementById('birthRegNum').value,
            contact: document.getElementById('contact').value,
            email: document.getElementById('email').value,
            reason: document.getElementById('reason').value,
            message: document.getElementById('message').value
        };

        try {
            const response = await fetch('/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            alert(result.message);

            if (response.ok) {
                document.getElementById('reissueForm').reset();
            }

        } catch (err) {
            alert('Error submitting the form');
        }
});

function submitForm(event) {
    event.preventDefault();
    window.location.href = "index.html";
}