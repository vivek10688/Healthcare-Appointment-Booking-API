<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Healthcare API Documentation</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f8;
        color: #333;
        padding: 30px;
    }

    h1 {
        text-align: center;
        margin-bottom: 50px;
        font-size: 2.2em;
        color: #111827;
    }

    .api-section {
        background: #fff;
        border-left: 5px solid #3b82f6;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .api-section:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }

    .api-section h2 {
        margin-top: 0;
        color: #111827;
        font-size: 1.5em;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    .subsection {
        margin-bottom: 25px;
    }

    .method {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 5px;
        font-weight: bold;
        color: #fff;
        margin-right: 10px;
        font-size: 0.9em;
        text-transform: uppercase;
    }

    .method.post { background: #10b981; }  
    .method.get { background: #3b82f6; }   
    .method.patch { background: #f59e0b; } 
    .method.delete { background: #ef4444; }

    .endpoint {
        font-weight: bold;
        font-size: 1.05em;
        color: #111827;
    }

    pre {
        background: #f3f4f6;
        padding: 20px;
        border-radius: 8px;
        overflow-x: auto;
        font-family: Consolas, monospace;
        line-height: 1.5;
        position: relative;
        min-height: 100px;
    }

    .response {
        background: #e0f2fe;
        padding: 15px;
        border-left: 5px solid #3b82f6;
        margin-top: 10px;
        border-radius: 6px;
    }

    button.copy-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #3b82f6;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.8em;
    }

    button.copy-btn:hover { background: #2563eb; }

    .toggle-btn {
        cursor: pointer;
        color: #3b82f6;
        font-size: 0.9em;
        margin-top: 5px;
        display: inline-block;
    }

    .hidden { display: none; }

    ul { padding-left: 20px; }
    li { margin-bottom: 5px; }

    @media (max-width: 768px) {
        body { padding: 15px; }
        pre { font-size: 13px; padding: 15px; }
    }
</style>
</head>
<body>

<h1>📘 Healthcare API Documentation</h1>

<!-- Authentication -->
<div class="api-section">
    <h2>Authentication</h2>

    <!-- Register -->
    <div class="subsection">
        <span class="method post">POST</span>
        <span class="endpoint">/api/auth/register</span>
        <p><strong>Content-Type:</strong> application/json</p>
        <strong>Request:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
            {
                "name": "John Doe",
                "email": "john@example.com",
                "password": "Password123!",
                "password_confirmation": "Password123!"
            }
        </pre>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
                {
                    "success": true,
                    "message": "User registered successfully",
                    "data": {
                        "user": {
                            "id": 13,
                            "name": "John Doe",
                            "email": "john@example.com",
                            "created_at": "2025-10-08T04:58:09.000000Z"
                        },
                        "token": "2|fhN6J1qfl8HaAHMgUEcG8vXui5BnGsspmN2mPTyjc8af3698"
                    }
                }
            </pre>
        </div>
    </div>

    <!-- Login -->
    <div class="subsection">
        <span class="method post">POST</span>
        <span class="endpoint">/api/auth/login</span>
        <p><strong>Content-Type:</strong> application/json</p>
        <strong>Request:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
            {
                "email": "john@example.com",
                "password": "Password123!"
            }
        </pre>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
                {
                    "success": true,
                    "message": "Login successful",
                    "data": {
                        "user": {
                            "id": 13,
                            "name": "John Doe",
                            "email": "john@example.com",
                            "created_at": "2025-10-08T04:58:09.000000Z"
                        },
                        "token": "3|sirxijKAXkmDN3Pf3vSKK3pdbnFdNUhHHWrO8PII9ce33f26"
                    }
                }
            </pre>
        </div>
    </div>

    <!-- Logout -->
    <div class="subsection">
        <span class="method post">POST</span>
        <span class="endpoint">/api/auth/logout</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
                {
                    "success": true,
                    "message": "Logged out successfully"
                }
            </pre>
        </div>
    </div>
</div>

<!-- Healthcare Professionals -->
<div class="api-section">
    <h2>Healthcare Professionals</h2>

    <div class="subsection">
        <span class="method get">GET</span>
        <span class="endpoint">/api/healthcare-professionals</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <strong>Response:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Mr. Tatum Larson DDS",
            "specialty": "Pediatrics",
            "bio": "Quia ipsum sint doloremque illum. Sapiente sed perferendis sequi optio. Qui odit recusandae fugit quis at.",
            "phone": "1-612-506-1894",
            "email": "ujohnson@example.org",
            "is_available": true
        },
        {
            "id": 2,
            "name": "Miss Alexandria Feeney",
            "specialty": "Orthopedics",
            "bio": "Omnis autem ut minus omnis omnis sunt. Voluptate distinctio esse autem et deleniti omnis. Quisquam perferendis molestias natus incidunt architecto autem. Reiciendis fugit dolorum enim porro omnis dolorum.",
            "phone": "+1.615.788.2968",
            "email": "xsatterfield@example.org",
            "is_available": true
        },
    ]
}
</pre>
    </div>
</div>

<!-- Appointments -->
<div class="api-section">
    <h2>Appointments</h2>

    <div class="subsection">
        <span class="method post">POST</span>
        <span class="endpoint">/api/appointments</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <p><strong>Content-Type:</strong> application/json</p>
        <strong>Request:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
"healthcare_professional_id": 1,
"appointment_start_time": "2025-10-15T10:00:00Z",
"appointment_end_time": "2025-10-15T11:00:00Z",
"notes": "Annual checkup"
}</pre>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
    "success": true,
    "message": "Appointment booked successfully",
    "data": {
        "id": 1,
        "healthcare_professional": {
            "id": 1,
            "name": "Mr. Tatum Larson DDS",
            "specialty": "Pediatrics",
            "bio": "Quia ipsum sint doloremque illum. Sapiente sed perferendis sequi optio. Qui odit recusandae fugit quis at.",
            "phone": "1-612-506-1894",
            "email": "ujohnson@example.org",
            "is_available": true
        },
        "appointment_start_time": "2025-10-15T10:00:00.000000Z",
        "appointment_end_time": "2025-10-15T11:00:00.000000Z",
        "status": "booked",
        "notes": "Annual checkup",
        "cancellation_reason": null,
        "created_at": "2025-10-08T05:31:06.000000Z"
    }
}</pre>
        </div>
    </div>

    <div class="subsection">
        <span class="method get">GET</span>
        <span class="endpoint">/api/appointments</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <strong>Response:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
    "success": true,
    "data": [
        {
            "id": 1,
            "healthcare_professional": {
                "id": 1,
                "name": "Mr. Tatum Larson DDS",
                "specialty": "Pediatrics",
                "bio": "Quia ipsum sint doloremque illum. Sapiente sed perferendis sequi optio. Qui odit recusandae fugit quis at.",
                "phone": "1-612-506-1894",
                "email": "ujohnson@example.org",
                "is_available": true
            },
            "appointment_start_time": "2025-10-15T10:00:00.000000Z",
            "appointment_end_time": "2025-10-15T11:00:00.000000Z",
            "status": "booked",
            "notes": "Annual checkup",
            "cancellation_reason": null,
            "created_at": "2025-10-08T05:31:06.000000Z"
        }
    ]
}</pre>
    </div>

    <div class="subsection">
        <span class="method patch">PATCH</span>
        <span class="endpoint">/api/appointments/{id}/cancel</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <p><strong>Content-Type:</strong> application/json</p>
        <strong>Request:</strong>
        <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
"cancellation_reason": "Schedule conflict"
}</pre>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
"success": true,
"message": "Appointment cancelled successfully"
}</pre>
        </div>
    </div>

    <div class="subsection">
        <span class="method patch">PATCH</span>
        <span class="endpoint">/api/appointments/{id}/complete</span>
        <p><strong>Authorization:</strong> Bearer {token}</p>
        <span class="toggle-btn" onclick="toggleResponse(this)">Show Response</span>
        <div class="response hidden">
            <strong>Response:</strong>
            <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
"success": true,
"message": "Appointment marked as completed"
}</pre>
        </div>
    </div>
</div>

<!-- Testing -->
<div class="api-section">
    <h2>Testing</h2>
    <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>php artisan test
php artisan test --filter AuthenticationTest
php artisan test --coverage</pre>
</div>

<!-- Error Handling -->
<div class="api-section">
    <h2>Error Handling</h2>
    <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
"success": false,
"message": "Error message",
"errors": { "field": ["validation error"] }
}</pre>
    <p><strong>HTTP Status Codes:</strong></p>
    <ul>
        <li>200: Success</li>
        <li>201: Created</li>
        <li>400: Bad Request</li>
        <li>401: Unauthorized</li>
        <li>403: Forbidden</li>
        <li>404: Not Found</li>
        <li>422: Validation Error</li>
        <li>500: Server Error</li>
    </ul>
</div>

<script>
function copyCode(btn){
    const code = btn.nextElementSibling.innerText;
    navigator.clipboard.writeText(code);
    btn.innerText = 'Copied!';
    setTimeout(()=>btn.innerText='Copy', 1500);
}

function toggleResponse(btn){
    const responseDiv = btn.nextElementSibling;
    responseDiv.classList.toggle('hidden');
    btn.innerText = responseDiv.classList.contains('hidden') ? 'Show Response' : 'Hide Response';
}
</script>

</body>
</html>
