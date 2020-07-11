import React from 'react';

export default function Footer() {
    return (
        <footer className="d-sm-none d-md-block text-muted bg-white">
            <div className="container">
                <div className="row justify-content-center mt-3">
                    <div className="col-md-8 col-sm-6 col-xs-12">
                        <p className="copyright-text">Copyright &copy; 2020 SOFTPROD | All Rights Reserved by <a href={'https://www.linkedin.com/in/sergio-gurillo-corral-2585431b0/'}
                               className={"text-primary"}>Sergio Gurillo Corral - Web Developer</a> </p>
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