# oligowizard-api-wrapper

A lightweight wrapper for sending requests to the Oligowizard API.  
Includes examples for authentication, queries, and result parsing.

Paid Subscription is required to access the API!


## Quick Start Guide

1. **Sign Up For Oligowizard Premium**  
Go to [Oligowizard.com](https://www.oligowizard.com/login) register with your email address and sign-up for a premium plan.  
Student / academic discounts and free trials are available!

2. **Download The Code**  
Grab the python or PHP wrapper from the repo:  
[`owapi.py`](https://github.com/Oligowizard/oligowizard-api-wrapper/blob/main/owapi.py)  
[`owapi.php`](https://github.com/Oligowizard/oligowizard-api-wrapper/blob/main/owapi.php)  

3. **Enter your API Key and auth token**  
Copy and paste the API Key and Cloudflare auth token from you [API Dashboard](https://www.oligowizard.com/api)  in the header  
`API_KEY = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"`  
`CF_client_id = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"`  
`CF_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"`  

4. **Send your first request**  
The wrappers come with a short set of example queries to test the connection and get you started  
`~python3 owapi.py`

6. **Customise & Implement**  
You're all set to define your own queries and implement them in your workflow!  
You can find a detailed documentation of the endpoints and variable names in the [documentation](https://github.com/Oligowizard/oligowizard-api-wrapper/blob/main/Documentation.md)