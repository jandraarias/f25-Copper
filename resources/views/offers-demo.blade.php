<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Business Offers Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f8fafc;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .offer {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin: 15px auto;
            padding: 20px;
            max-width: 600px;
        }
        .offer h3 {
            margin: 0;
            color: #007BFF;
        }
        .offer p {
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Business Offers</h1>
    <div id="offers"></div>

    <script>
        fetch('/api/business-offers')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('offers');
                if (data.length === 0) {
                    container.innerHTML = "<p>No offers found.</p>";
                    return;
                }

                data.forEach(offer => {
                    const div = document.createElement('div');
                    div.className = 'offer';
                    div.innerHTML = `
                        <h3>${offer.title}</h3>
                        <p>${offer.description}</p>
                        <p><strong>Starts:</strong> ${offer.starts_at}</p>
                        <p><strong>Ends:</strong> ${offer.ends_at}</p>
                    `;
                    container.appendChild(div);
                });
            })
            .catch(error => {
                document.getElementById('offers').innerHTML = "<p>Error loading offers.</p>";
                console.error(error);
            });
    </script>
</body>
</html>