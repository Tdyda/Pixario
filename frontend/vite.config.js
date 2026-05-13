import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import fs from 'fs'
import path from 'path'

export default defineConfig({
    plugins: [react()],
    server: {
        host: '192.168.50.203',   // ważne, inaczej serwer nie słucha na IP
        port: 5173,
        // https: {
        //     key: fs.readFileSync(path.resolve(__dirname, 'cert/192.168.50.203-key.pem')),
        //     cert: fs.readFileSync(path.resolve(__dirname, 'cert/192.168.50.203.pem')),
        // },
    }
})
