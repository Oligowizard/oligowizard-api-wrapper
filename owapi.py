"""
Oligowizard API Python Wrapper
(C) OLIGOWIZARD LTD
VERSION 1.0
2025
A lightweight Python wrapper for sending requests to the Oligowizard API. Includes examples for authentication, sequence queries, and result parsing.
https://github.com/Oligowizard/oligowizard-api-wrapper
"""

import requests # dependency for HTTP requests
# https://pypi.org/project/requests/
# pip install requests


# Set authentication tokens (ACTION REQUIRED)
API_KEY = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" # Treat your API key like a password - this key links your requests to your account
CF_client_id = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
CF_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"

# Set HTTP requests headers
URL = "https://api.oligowizard.app"
headers = {
    "API-Key": API_KEY,
    "Content-Type": "application/json",
    "CF-Access-Client-Id": CF_client_id,
    "CF-Access-Client-Secret": CF_secret
}


def test_connection():
    """
    Function to confirm succesful connection to the server.

    Expected behavior:
    200 (the response body will contain the user's limits and used quota)

    Possible Errors:
    401: Incorrect or expired API token (API_KEY)
    403: CF token pair missing or incorrect (CF_client_id , CD_secret)
    418: Wrong request type (get instead of post)
    502: Server is currently offline or incorrectly configured
    """
    try:
        response = requests.post(URL, headers=headers)
        print(response)
        #print(response.text) # uncomment for more detailed response
        if(response.status_code == 200):
            return(True)
        
        else:
            return(False)
    
    except:
        return(False)

def advanced(sequence, A260=1.0, three_prime="OH", five_prime="OH", five_is_PS="FALSE", Na_conc=50, K_conc=0, Mg_conc=0):

    full_URL = URL + "/advanced"

    payload = {
        "sequence": sequence,
        "three_prime": three_prime,
        "five_prime": five_prime,
        "A260": A260,
        "five_is_PS": five_is_PS,
        "Na_conc": Na_conc,
        "K_conc": K_conc,
        "Mg_conc": Mg_conc
    }

    response = requests.post(full_URL, headers=headers, json=payload)

    try:
        data = response.json()
        return(data)

    except:
        print(response.status_code)
        return(False)

def convert(sequence, input_code, output_code):
    full_URL = URL + "/convert"

    ribose_mods ={
        "DNA": 0,
        "RNA": 1,
        "LNA": 2,
        "MOE": 3,
        "OMe": 4,
        "2'F": 5
    }

    input_code = ribose_mods[input_code]
    output_code = ribose_mods[output_code]

    payload = {
        "sequence": sequence,
        "input_code": input_code,
        "output_code": output_code
    }

    response = requests.post(full_URL, headers=headers, json=payload)

    try:
        data = response.json()
        return(data)
    
    except:
        print(response.status_code)
        return(False)

def structure(sequence, scale=0.45, size=12, width=1, face=96, filename=None):
    full_URL = URL + "/structure"
    payload = {
        "sequence": sequence,
        "scale": scale,
        "size": size,
        "width": width,
        "face": face
    }

    response = requests.post(full_URL, headers=headers, json=payload)

    if response.status_code == 200:
        try:
            if(filename):
                target_path = filename
            else:
                content_disp = response.headers.get("Content-Disposition", "")
                target_path = content_disp[21:]

            with open(target_path, "wb") as outfile:
                outfile.write(response.content)

            return(target_path)
        
        except:
            print("failed to retrieve structure file")
            return(False)
    
    else:
        print(response.status_code)
        return(False)




if __name__ == '__main__':

    if(test_connection()): # Only run the queries if the connection is good

        example_sequence = "tcactttcataatgctgg" # Nusinersen sequence as DNA

        nus_moe = convert(example_sequence,"DNA","MOE")["output"] # Turn Nusinersen DNA sequence into MOE
        
        example_query = advanced(nus_moe) # grab all data for the sequence (mass, tm, extinction, concentration etc..)

        print(example_query["molext"]) # Print molar extinction value (in L mol-1 cm-1))
        print(str(example_query["mass3"]) + " g/mol " + example_query["mass3_text"]) # Also print DMT-ON weight (output here cast as a string to concatenate with explanation text)

        structure(nus_moe, filename="Nusinersen_structure.cdxml") # Draw the structure and save it under the filename

    else:
        print("Connection Error")
    
    


    