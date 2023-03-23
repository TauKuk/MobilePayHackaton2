import './App.scss';

import NavBar from '../Components/NavBar/NavBar';
import LandingPage from '../Components/LandingPage/LandingPage';
import Footer from '../Components/Footer/Footer';

function App() {
  return (
      <div className="App">
          <main>        
            <NavBar />
            <LandingPage />
          </main>
        <Footer />
      </div>
  );
}

export default App;