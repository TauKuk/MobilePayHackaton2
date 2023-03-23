import React from "react";

import NavBarAuth from "../Components/NavBarAuth/NavBarAuth";
import Footer from "../Components/Footer/Footer";
import HomePage from "../Components/HomePage/HomePage";

export default function Home() {
    return (
        <div className="App">
            <main>                
                <NavBarAuth />
                <HomePage />
            </main> 
            
            <Footer />
        </div>
    );
}