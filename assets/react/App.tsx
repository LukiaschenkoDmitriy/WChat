import React, { StrictMode } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { createRoot } from "react-dom/client";
import Chat from "./components/Chat";

const container = document.getElementById("root") as HTMLElement;
console.log(container);
const root = createRoot(container!);
root.render(
    <StrictMode>
        <BrowserRouter>
            <Routes>
                <Route path="/api/chat/:reactRouting" element={<Chat />} />
            </Routes>
        </BrowserRouter>
    </StrictMode>
)