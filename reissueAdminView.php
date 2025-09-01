<?php
include "database.php";

$sql = "SELECT applicantName, birthRegistrationNum, contactNum, email, reason, additionalInfo FROM reissueapp_t";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Birth Record Communication System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }


        .header {
            background: linear-gradient(135deg, #1a56db 0%, #3b82f6 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }


        .adminSection {
            padding: 2rem;
            min-height: calc(100vh - 160px);
        }

        .adminContainer {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .adminContainer h2 {
            color: #1e40af;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .adminContainer h2 i {
            background: #e0e7ff;
            padding: 10px;
            border-radius: 50%;
        }


        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }


        .applications-table th {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: white;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
        }

        .applications-table th:first-child {
            border-top-left-radius: 8px;
        }

        .applications-table th:last-child {
            border-top-right-radius: 8px;
        }

        .applications-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }


        .applications-table tbody tr {
            background-color: white;
        }

        .applications-table tbody tr:hover {
            background-color: #f9fafb;
        }


        .applicant-name {
            font-weight: 600;
        }


        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .accept-btn, .reject-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .accept-btn {
            background-color: #10b981;
            color: white;
        }

        .accept-btn:hover:not(:disabled) {
            background-color: #059669;
        }

        .reject-btn {
            background-color: #ef4444;
            color: white;
        }

        .reject-btn:hover:not(:disabled) {
            background-color: #dc2626;
        }

        .accept-btn:disabled, .reject-btn:disabled {
            background-color: #d1d5db;
            color: #9ca3af;
            cursor: not-allowed;
        }


        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }


        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .pagination button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pagination button:hover {
            background-color: #2563eb;
        }


        .footer {
            background-color: #1e293b;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }


        @media (max-width: 1024px) {
            .nav-links {
                gap: 0.8rem;
            }
            
            .nav-links a {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }
            
            .nav-links {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .adminSection {
                padding: 1rem;
            }
            
            .adminContainer {
                padding: 1.5rem;
            }
            
            .applications-table th, 
            .applications-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }


        @keyframes buttonClick {
            0% { transform: scale(1); }
            50% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }

        .accept-btn:active:not(:disabled),
        .reject-btn:active:not(:disabled),
        .pagination button:active {
            animation: buttonClick 0.2s;
        }
        

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.5rem 1rem;
        }
        
        .search-box input {
            border: none;
            outline: none;
            margin-left: 0.5rem;
            width: 200px;
        }
    </style>
</head>
<body>
    <Header class="header">
        <h1><i class="fas fa-certificate"></i> Birth Record Communication System</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="admin_dashboard.html"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-syringe"></i> Vaccination</a></li>
                <li><a href="correctionViewAdmin.html"><i class="fas fa-edit"></i> Correction Request</a></li>
                <li><a href="dataAnalysis.html">Data Analysis</a></li>
                <li><a href="#"><i class="fas fa-history"></i> Application History</a></li>
                <li><a href="index.html" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </Header>
   
    <section class="adminSection">
        <div class="adminContainer">
            <h2><i class="fas fa-file-contract"></i> Reissue Applications</h2>
            
            <div class="table-header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search applications...">
                </div>
            </div>
            
            <div class="table-container">
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>Applicant Name</th>
                            <th>Registration No.</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Additional Info</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $count = 0;
                            while ($row = $result->fetch_assoc()) {
                                $count++;
                        ?>
                        <tr>
                            <td><span class="applicant-name"><?php echo $row['applicantName']; ?></span></td>
                            <td><?php echo $row['birthRegistrationNum']; ?></td>
                            <td><?php echo $row['contactNum']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><?php echo $row['additionalInfo']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="accept-btn" onclick="processApplication(this, 'accept')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button class="reject-btn" onclick="processApplication(this, 'reject')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center;'>No applications found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <button id="prevBtn"><i class="fas fa-chevron-left"></i> Previous</button>
                <span id="pageInfo">Page 1 of 3</span>
                <button id="nextBtn">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
   
    <footer class="footer">
        <p>© 2023 Birth Record Communication System. All rights reserved.</p>
    </footer>

    <script>
        function processApplication(button, action) {
            const row = button.closest('tr');
            const statusCell = row.querySelector('td:nth-child(6)');
            const buttons = row.querySelectorAll('.accept-btn, .reject-btn');
            
            if (action === 'accept') {
                statusCell.innerHTML = '<span class="status-badge status-approved">Approved</span>';
                buttons.forEach(btn => btn.disabled = true);
                button.classList.add('active');
            } else if (action === 'reject') {
                statusCell.innerHTML = '<span class="status-badge status-rejected">Rejected</span>';
                buttons.forEach(btn => btn.disabled = true);
                button.classList.add('active');
            }
            

            console.log(`Application ${action}ed`);
        }
        

        document.getElementById('nextBtn').addEventListener('click', function() {
            const pageInfo = document.getElementById('pageInfo');
            const currentPage = parseInt(pageInfo.textContent.match(/\d+/)[0]);
            if (currentPage < 3) {
                pageInfo.textContent = `Page ${currentPage + 1} of 3`;
            }
        });
        
        document.getElementById('prevBtn').addEventListener('click', function() {
            const pageInfo = document.getElementById('pageInfo');
            const currentPage = parseInt(pageInfo.textContent.match(/\d+/)[0]);
            if (currentPage > 1) {
                pageInfo.textContent = `Page ${currentPage - 1} of 3`;
            }
        });
        
        function logout() {
            alert('Logging out...');

        }
        

        document.querySelector('.search-box input').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('.applications-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>