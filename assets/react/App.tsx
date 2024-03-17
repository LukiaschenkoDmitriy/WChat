import React, { StrictMode } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { createRoot } from "react-dom/client";
import ChatSection from "./components/ChatSection";
import "./react.scss";

const container = document.getElementById("root") as HTMLElement;
const root = createRoot(container!);
root.render(
    <StrictMode>
        <BrowserRouter>
            <Routes>
                <Route path="/app/chat/" element={<ChatSection />} />
            </Routes>
        </BrowserRouter>
    </StrictMode>
)