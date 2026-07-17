# See what's on the port
netstat -ano | findstr :8081

# Kill it (use the PID from the last column)
taskkill /F /PID <PID>


# One-liner — kill everything on a port
Get-NetTCPConnection -LocalPort 8081 -State Listen | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force }

# Nuclear option — kill every Node process
taskkill /F /IM node.exe
# Check nothing's left
netstat -ano | findstr "4000 8081"
tasklist /FI "IMAGENAME eq node.exe"
