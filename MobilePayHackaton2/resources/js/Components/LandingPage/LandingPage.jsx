import React from "react";

import "./LandingPage.scss"

import LandingTileData from "../../data/LandingTileData";
import StravaLogin from "../StravaLogin/StravaLogin";

export default function LandingPage() {

    const LandingTiles = LandingTileData.map((tile) => {
        return (
            <div className="landing--image">
                <div className={tile.img + " landing--tile"}>
                    <img src={"/Images/" + tile.img + ".png"}></img>
                </div> 

                <div className="landing--tile--title">{ tile.txt }</div>
            </div>
        )
    })

    return (
        <>
            <div className="landing--main--container">
                <div className="landing--page--center"> 
                    <div className="landing--info">
                        <div className="landing--text">
                            <h2 className="landing--name">MobilePay Sports</h2>

                            <div className="landing--description">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                            </div>   
                        </div>

                        <div className="landing--popup--animation">
                            <button className="landing--button"><StravaLogin /></button>
                        </div>
                    </div>
                </div>
            </div>

            <div className="landing--challenges">
                <h3 className="challenges--title">Prisijunk prie iššūkio</h3>
                <div className="landing--tiles">
                    { LandingTiles }
                </div>
            </div>

        </>
    )
}