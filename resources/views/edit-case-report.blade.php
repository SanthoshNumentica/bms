@if(session('sendWhatsappPrompt'))
    <script>
        const recordId = @json(session('sendWhatsappPrompt'));
        const userConfirmed = confirm("Do you want to send the documents via WhatsApp?");
        if (userConfirmed) {
            // Call your backend endpoint to send the documents to WhatsApp
            fetch(`/send-whatsapp-documents/${recordId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ recordId: recordId })
            })
            .then(response => response.json())
            .then(data => {
                alert("Documents sent via WhatsApp!");
            })
            .catch(error => {
                alert("There was an error sending the documents.");
            });
        }
    </script>
@endif
