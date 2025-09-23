<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Complaint Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            padding: 3rem 0;
        }

        .card {
            width: 500px;
            padding: 2.5rem;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step.active {
            display: block;
        }

        .btn-step {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }

        .btn-step:hover {
            transform: scale(1.05);
        }

        h5 {
            color: #333;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            border-radius: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <form id="complaintForm">
            <!-- Step 1 -->
            <div class="step active" id="step1">
                <h5 class="mb-3">Complainant Details</h5>
                <input type="text" name="name" class="form-control mb-2" placeholder="Enter your Name" >
                <input type="text" name="phone" class="form-control mb-2" placeholder="Enter your Phone" >
                <input type="email" name="email" class="form-control mb-2" placeholder="Enter your Email">
                <input type="text" name="address" class="form-control mb-2" placeholder="Enter your Address">
                <input type="text" name="aadhaar" class="form-control mb-2" placeholder="Enter your Aadhaar No.">

                <select name="complaint_category" id="complaintCategory" class="form-select mb-3">
                    <option value="" selected disabled>Select Complaint Type</option>
                    <option value="vat">VAT</option>
                    <option value="gst">GST</option>
                    <option value="excise">Excise</option>
                </select>

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step" id="step2">
                <h5 class="mb-3">GST Fraud/Evasion Check</h5>
                <p>Is complaint related to Fraud/Evasion?</p>

                <div class="d-flex flex-column">
                    <select id="fraudCheck" class="form-select mb-2" required>
                        <option value="" selected disabled>Select an option</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-primary btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- Step 3: Final Submission -->
            <div class="step" id="step3">
                <h5 class="mb-3">Complaint Details</h5>
                <input type="text" name="firm_name" class="form-control mb-2" placeholder="Enter Firm Name" required>
                <input type="text" name="address_detail" class="form-control mb-2" placeholder="Enter Address"
                    required>
                <input type="file" name="document" class="form-control mb-2" required>
                <textarea name="remarks" class="form-control mb-2" placeholder="Remarks" required></textarea>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="submit" class="btn btn-success btn-step">Submit Complaint</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            document.querySelectorAll('.step').forEach((el, i) => {
                el.classList.remove('active');
                if (i + 1 === step) el.classList.add('active');
            });
        }

        function nextStep() {
            if (!validateStep(currentStep)) return;

            const category = document.getElementById('complaintCategory')?.value;

            if (currentStep === 1 && category !== 'gst') {
                alert('Hello!');
                currentStep += 2;
            } else {
                currentStep++;
            }

            if (currentStep > totalSteps) currentStep = totalSteps;
            showStep(currentStep);
        }

        function prevStep() {
            const category = document.getElementById('complaintCategory')?.value;

            if (currentStep === 3 && category !== 'gst') {
                currentStep -= 2; // skip back to step 1
            } else {
                currentStep--;
            }

            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
        }

        function validateStep(step) {
            const stepEl = document.querySelector(`#step${step}`);
            const inputs = stepEl.querySelectorAll('input, select, textarea');

            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        document.getElementById('complaintForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateStep(currentStep)) return;

            const formData = new FormData(this);

            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            alert('Complaint submitted successfully!');
            this.reset();
            currentStep = 1;
            showStep(currentStep);
        });
    </script>
</body>

</html>
