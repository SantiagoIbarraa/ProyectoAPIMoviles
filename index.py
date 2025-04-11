from google import genai



while 1:    
    client = genai.Client(api_key="AIzaSyAarhbLF1-cFYUwKgKWc8dEFhRLuTELOz0")
    test="a"
    test = input("Enter your text: ") 

    response = client.models.generate_content(
        model="gemini-2.0-flash",
        contents=[test]
    )
    print(response.text)
