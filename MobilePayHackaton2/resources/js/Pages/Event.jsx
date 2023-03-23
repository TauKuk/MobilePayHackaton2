import React from 'react';

import NavBarAuth from '../Components/NavBarAuth/NavBarAuth';
import Footer from '../Components/Footer/Footer';
import EventViewContent from '../Components/EventViewContent/EventViewContent';

function Event() {
  return (
    <div className="App">
        <main>        
            <NavBarAuth />
            <EventViewContent />
        </main>
        <Footer />
    </div>
  );
}

export default Event;