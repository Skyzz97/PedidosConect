Set shell = CreateObject("WScript.Shell")

projectPath = "C:\php\www"
phpPath = "C:\php\php.exe"

' Cria comando para rodar o PHP sem console (usando wscript em vez de cmd/start)
cmd = """" & phpPath & """ -S localhost:8000 -t """ & projectPath & """"

' Executa em background invisível
shell.Run cmd, 0, False

' Espera o servidor subir
WScript.Sleep 2000

' Abre o navegador
shell.Run "http://localhost:8000/index.html"
