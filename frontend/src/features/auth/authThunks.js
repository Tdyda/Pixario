import { createAsyncThunk } from "@reduxjs/toolkit";
import { api } from "../../api/axios.js";

export const fetchCurrentUser = createAsyncThunk(
    'auth/fetchCurrentUser',
    async () => {
        const response = await api.get('/auth/me')
        return response.data.user;
    }
)