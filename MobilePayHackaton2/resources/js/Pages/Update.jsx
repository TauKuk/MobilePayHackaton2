import Footer from '../Components/Footer/Footer';
import UpdateForm from '../Components/UpdateForm/UpdateForm';
import NavBarAuth from '../Components/NavBarAuth/NavBarAuth';

export default function Update() {
  return (
      <div className="App">
          <main>        
            <NavBarAuth />
            <UpdateForm />
          </main>
        <Footer />
      </div>
  );
}
