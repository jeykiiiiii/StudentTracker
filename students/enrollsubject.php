<!DOCTYPE html>
<html lang="en">

<head>
    <title>Available Subjects</title>
    <link rel="stylesheet" href="../assets/css/liststyle.css">
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Push button to bottom */
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
            background: #fff;
            min-height: 250px; /* Ensure consistent height for all cards */
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 1.5rem;
        }

        .card p {
            margin: 5px 0;
        }

        .card-footer {
            margin-top: auto; /* Push the button to the bottom */
        }

        .enroll-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .enroll-btn:hover {
            background-color: #45a049;
        }

        .headerH2 {
            margin: 20px;
        }
    </style>
</head>

<body>
    <main>
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sideNavStudent.php"); ?>

        <h2 class="headerH2">Available Subjects</h2>
        <div id="cardContainer" class="card-container"></div>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('../functions/get_availableSubjects.php')
                .then(response => response.json())
                .then(data => {
                    const cardContainer = document.querySelector("#cardContainer");
                    cardContainer.innerHTML = "";

                    if (data.length === 0) {
                        cardContainer.innerHTML = "<p>No available subjects.</p>";
                        return;
                    }

                    data.forEach(row => {
                        const card = document.createElement("div");
                        card.className = "card";
                        card.innerHTML = `
                            <div>
                                <h3>${row.subject_name}</h3>
                                <p><strong>Instructor:</strong> ${row.instructor_name}</p>
                                <p><strong>Schedule:</strong> ${row.schedule}</p>
                            </div>
                            <div class="card-footer">
                                <button class="enroll-btn" onclick="enroll(${row.class_id})">Enroll</button>
                            </div>
                        `;
                        cardContainer.appendChild(card);
                    });
                })
                .catch(error => console.error('Error fetching available subjects:', error));
        });

        function enroll(classId) {
            if (!confirm("Are you sure you want to enroll in this subject?")) return;

            fetch('../functions/enroll.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `class_id=${classId}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    location.reload(); // Refresh the page to show updated subjects
                }
            })
            .catch(error => console.error('Error during enrollment:', error));
        }
    </script>

</body>

</html>
