from google import genai

client = genai.Client(api_key="AIzaSyAarhbLF1-cFYUwKgKWc8dEFhRLuTELOz0")

response = client.models.generate_content(
    model="gemini-2.0-flash",
    contents=["Que club es conocido por quemar su cancha de futbol cuando descendieron de categoría en 2011?S"],
)
print(response.text)