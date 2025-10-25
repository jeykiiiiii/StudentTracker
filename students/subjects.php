<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Enrolled Subjects</title>
    <link rel="stylesheet" href="../assets/css/liststyle.css">

    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
            background: #fff;
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

        .view-btn, .drop-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .view-btn {
            background-color: #4CAF50;
        }

        .view-btn:hover {
            background-color: #45a049;
        }

        .drop-btn {
            background-color: #f44336;
            margin-left: 10px;
        }

        .drop-btn:hover {
            background-color: #d32f2f;
        }

        .headerH2 {
            margin: 20px;
        }
    </style>

</head>

<body>
    <main>
        <?php include("../includes/header.php"); ?>
        <?php include('../includes/sideNavStudent.php'); ?>

        <h2 class="headerH2">Your Enrolled Subjects</h2>

        <div class="card-container" id="cardContainer"></div>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('../functions/get_studentSubjects.php')
                .then(response => response.json())
                .then(data => {
                    const cardContainer = document.getElementById("cardContainer");
                    cardContainer.innerHTML = "";

                    if (data.length === 0) {
                        cardContainer.innerHTML = "<p>No enrolled subjects available.</p>";
                        return;
                    }

                    data.forEach(row => {
                        const card = document.createElement("div");
                        card.className = "card";
                        card.innerHTML = `
                            <h3>${row.subject_name}</h3>
                            <p><strong>Instructor:</strong> ${row.instructor_name}</p>
                            <p><strong>Schedule:</strong> ${row.schedule}</p>
                            <button class="view-btn" onclick="viewDetails(${row.class_id})">View</button>
                            <button class="drop-btn" onclick="dropSubject(${row.class_id})">Drop</button>
                        `;
                        cardContainer.appendChild(card);
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));
        });

        function viewDetails(classId) {
            window.location.href = `subjectDetails.php?class_id=${classId}`;
        }

        function dropSubject(classId) {
            if (!confirm("Are you sure you want to drop this subject?")) return;

            fetch('../functions/drop_subject.php', {
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
                    location.reload(); // Refresh page to update the list
                }
            })
            .catch(error => console.error('Error dropping subject:', error));
        }
    </script>

</body>

</html>
