<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | JMA Trucks Solution</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- FontAwesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        
        body {
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Background gradient */
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 800px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
            padding: 20px;
        }

        .form-container h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: #ff4c4c;
        }

        .form-control {
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #ff4c4c;
        }

        .file-upload {
            margin-bottom: 20px;
        }

        .file-upload label {
            display: block;
            background-color: rgba(255, 255, 255, 0.2);
            border: 2px dashed #ff4c4c;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            color: #ff4c4c;
        }

        .file-upload img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 10px;
            margin: 10px auto;
            display: none;
        }

        .btn-custom {
            background-color: #ff4c4c;
            color: white;
            border-radius: 25px;
            padding: 12px 25px;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #e32f2f;
        }

        .terms-link {
            font-size: 14px;
            color: #ff4c4c;
        }

        .terms-link a {
            color: #ff4c4c;
            text-decoration: underline;
        }

        .terms-link a:hover {
            text-decoration: none;
        }
        

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 0 10px;
            }

            .form-container h1 {
                font-size: 24px;
            }

            .form-control {
                margin-bottom: 15px;
            }

            .btn-custom {
                font-size: 16px;
            }
        }
            .position-relative {
                position: relative;
        }

            .fas.fa-eye {
                color: #aaa; /* Optional: Change the color to gray */
                font-size: 18px; /* Adjust the size if necessary */
            }
            .form-group.position-relative {
                position: relative;
            }

            #togglePassword {
                position: absolute;
                top: 50%;
                right: 15px; /* Adjust the right value as needed */
                transform: translateY(-50%);
                cursor: pointer;
                color: #aaa; /* Optional: Change the color to gray */
                font-size: 18px; /* Adjust the size if necessary */
            }


        @media (max-width: 576px) {
            .file-upload label {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container signup-container">
        <div class="form-container">
            <h1>Sign Up</h1>
            
            
            <form action="register.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="contact" class="form-control" placeholder="Contact" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="number" name="age" class="form-control" placeholder="Age" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                    <div class="form-group position-relative">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <select name="govt_id" class="form-control" required>
                            <option value="" disabled selected>Government Valid ID/Business License ID</option>
                            <option value="business_permit">Business or Mayor's Permit</option>
                            <option value="dti_permit">DTI Business Name Registration</option>
                            <option value="brgy_clearance">Barangay Clearance</option>
                            <option value="cert_of_registration">Certificate of Registration (COR) or BIR Form 2303</option>
                        </select>
                    </div>
                </div>
                <p>Upload supporting documents:</p>
                <div class="file-upload">
                    <label for="doc-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Choose your file to upload
                        <input type="file" id="doc-upload" name="doc-upload" class="form-control" accept="image/*" hidden onchange="previewImage(event)">
                    </label>
                    <img id="image-preview" alt="Image Preview">
                </div>
                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="terms-link">
                        I accept the <a href="#" data-toggle="modal" data-target="#termsModal">Terms of Use</a> 
                        and <a href="#" data-toggle="modal" data-target="#privacyModal">Privacy Policy</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-custom">Create an Account</button>
            </form>
        </div>
    </div>

    <!-- Modal for Terms of Use -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms of Use</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Terms of Use <br>
                        1. Introduction<br>
                        Welcome to JMA Trucks Solution. By accessing or using our platform, you agree to comply with and be bound by these Terms of Use. If you do not agree to these terms, you should not use the platform.<br><br>
                        2. Eligibility<br>
                        To use JMA Trucks Solution, you must be at least 18 years old and have the legal authority to enter into these Terms of Use.<br><br>
                        3. User Accounts<br>
                        Registration: You must register for an account to access certain features of our platform. When registering, you agree to provide accurate and complete information.<br>
                        Account Security: You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. If you suspect unauthorized use of your account, you must notify us immediately.<br><br>
                        4. Prohibited Activities<br>
                        While using JMA Trucks Solution, you agree not to:<br>
                        - Violate any applicable laws or regulations.<br>
                        - Use the platform for fraudulent purposes.<br>
                        - Post or transmit harmful, offensive, or illegal content.<br>
                        - Attempt to interfere with or disrupt the platform's services.<br><br>
                        5. Content Ownership<br>
                        All content provided by JMA Trucks Solution, including text, images, and graphics, is the property of JMA Trucks Solution or its licensors. You may not use, reproduce, or distribute any content without our prior written consent.<br><br>
                        6. Limitation of Liability<br>
                        JMA Trucks Solution is not responsible for any direct, indirect, incidental, or consequential damages arising from your use of the platform, including any loss of data or interruption of services.<br><br>
                        7. Modification of Terms<br>
                        We reserve the right to modify these Terms of Use at any time. Any changes will be effective immediately upon posting the revised terms on the platform. Your continued use of the platform signifies your acceptance of the updated terms.<br><br>
                        8. Governing Law<br>
                        These Terms of Use are governed by and construed in accordance with the laws of the Republic of the Philippines. Any legal disputes will be resolved in the courts of Philippines.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Privacy Policy -->
    <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Privacy Policy <br>
                        1. Introduction<br>
                        JMA Trucks Solution values your privacy and is committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and protect your data.<br><br>
                        2. Information We Collect<br>
                        We may collect the following types of personal information:<br>
                        - Personal Identification Information: Name, email address, contact details, and other information provided during registration.<br>
                        - Transaction Information: Records of your interactions and transactions within the platform.<br>
                        - Device Information: Details about the devices used to access our platform, including IP address, browser type, and operating system.<br><br>
                        3. How We Use Your Information<br>
                        We use the information we collect to:<br>
                        - Provide and improve our services.<br>
                        - Process transactions and communicate with you about your account.<br>
                        - Ensure platform security and prevent fraudulent activity.<br>
                        - Comply with legal obligations.<br><br>
                        4. Data Sharing<br>
                        We may share your personal information with:<br>
                        - Service Providers: Third-party providers who assist us with platform operations, such as payment processors and hosting services.<br>
                        - Legal Authorities: If required by law or in response to legal proceedings.<br>
                        - Business Transfers: In the event of a merger, acquisition, or sale of assets, your personal data may be transferred to the relevant parties.<br><br>
                        5. Cookies<br>
                        We use cookies and similar technologies to enhance your experience on JMA Trucks Solution. Cookies help us understand user behavior, remember your preferences, and deliver personalized content. You can disable cookies in your browser settings, but this may affect your use of the platform.<br><br>
                        6. Data Security<br>
                        We implement appropriate technical and organizational measures to protect your personal data from unauthorized access, loss, or alteration. However, no method of data transmission or storage is completely secure, and we cannot guarantee the absolute security of your data.<br><br>
                        7. Your Rights<br>
                        Depending on your location, you may have the following rights regarding your personal data:<br>
                        - Access: Request a copy of the personal data we hold about you.<br>
                        - Correction: Request that we correct any inaccurate or incomplete information.<br>
                        - Deletion: Request the deletion of your personal data, subject to legal obligations.<br>
                        - Restriction: Request the restriction of certain processing activities.<br>
                        - Portability: Request the transfer of your data to another organization.<br>
                        To exercise any of these rights, please contact us at jmatrucks@gmail.com.<br><br>
                        8. Changes to This Policy<br>
                        We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on the platform. Your continued use of the platform after changes to the Privacy Policy constitutes acceptance of the updated terms.<br><br>
                        9. Contact Us<br>
                        If you have any questions or concerns about this Privacy Policy, please contact us at jmatrucks@gmail.com.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // Toggle the eye / eye-slash icon
            this.classList.toggle('fa-eye-slash');
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Check for success message in the URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('signup') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: 'Please allow up to 24 hours for our team to verify your registration.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php';
                    }
                });
            }

        // Check if there is an error message in the session
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo addslashes($_SESSION['error']); ?>', // Escape quotes for JavaScript
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['error']); ?> // Unset the error after displaying it
        <?php endif; ?>   
        });
    </script>
</body>
</html>
