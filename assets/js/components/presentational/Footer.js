import React from 'react';

export default function Footer() {
    return (
        <footer className="text-muted">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8 col-sm-6 col-xs-12">
                        <p className="copyright-text">Copyright &copy; 2020 SOFTPROD | All Rights Reserved by <span className={"text-primary"}>Sergio Gurillo Corral - Web Developer</span> </p>
                    </div>

                    <ul className="social-icons">
                        <li><a className="github" href="https://github.com/Guuri11"><i className="fab fa-github"/></a></li>
                        <li><a className="linkedin" href="https://www.linkedin.com/in/sergio-gurillo-corral-2585431b0/">
                            <i className="fab fa-linkedin"/></a></li>
                    </ul>
                </div>
            </div>
        </footer>
    )
}