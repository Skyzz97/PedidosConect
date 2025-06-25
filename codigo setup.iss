[Setup]
AppName=Conect Pedidos
AppVersion=1.0
DefaultDirName={pf}\conect
DefaultGroupName=Conect
OutputDir=C:\php\www
OutputBaseFilename=conect-setup
Compression=lzma
SolidCompression=yes

[Files]
Source: "C:\php\*"; DestDir: "{app}"; Flags: recursesubdirs createallsubdirs

[Icons]
Name: "{group}\Iniciar Ambiente PHP"; Filename: "{app}\www\iniciar.vbs"
Name: "{commondesktop}\Iniciar Ambiente PHP"; Filename: "{app}\www\iniciar.vbs"; Tasks: desktopicon

[Tasks]
Name: "desktopicon"; Description: "Criar atalho na área de trabalho"; GroupDescription: "Atalhos:"
