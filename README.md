method: POST
API: http://127.0.0.1:8000/api/auth/register
Body: name, email, password, c_password
response: message, accessToken

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

method: POST
API: http://127.0.0.1:8000/api/auth/login
Body: email, password
response: accessToken, token_type

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

method: GET
API: http://127.0.0.1:8000/api/auth/logout
Headers: Accept: application/json, Authorization: Bearer <Token>
response: Successfully logged out

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

method: GET
API: http://127.0.0.1:8000/api/auth/user
Headers: Accept: application/json, Authorization: Bearer <Token>
response: user data

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

method: POST
API: http://127.0.0.1:8000/api/auth/login
Body: email, password
response: accessToken, token_type

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++