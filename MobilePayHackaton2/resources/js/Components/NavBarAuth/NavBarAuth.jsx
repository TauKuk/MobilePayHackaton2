import './NavBarAuth.scss';
import React, { useState } from 'react';

export default function NavBarAuth() {
    const [openBurger, setOpenBurger] = useState(false);

    function handleOpenBurger() {
        setOpenBurger(current => !current);
    }

    return (
        <div className="nav--auth--container">
            <nav className="nav--bar">
                <div className='nav--top nav--auth--top'>
                    <div className="nav--info">
                        <a href="/home#">
                            <img src="/Images/Logo.png" alt="Logo" className="logo" />
                        </a>
                    </div>

                    <input type="checkbox" defaultChecked={openBurger} className="toggle--btn" onClick={handleOpenBurger} id="toggle--btn" />

                    <label htmlFor="toggle--btn"  className="toggle--icon">
                        <span className="auth--bar bar"></span>
                    </label>  

                    <ul className="nav--links">
                        <li><a href="#">Atsijungti</a></li>
                    </ul>
                </div>
                <div className='burger--bottom'
                    style={{
                        opacity: (openBurger ? 1 : 0),
                        zIndex: (openBurger ? "inherit" : -1), 
                        transition: (openBurger ? "opacity 0.2s" : "opacity 0s")
                    }}>
                        <ul className='burger--links'>                            
                            <li onClick={handleOpenBurger}><a href="#">Atsijungti</a></li>
                        </ul>
                </div>
            </nav>
        </div>
    )
}