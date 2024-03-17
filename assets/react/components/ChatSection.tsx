import React, { Component } from "react";
import { ChatData } from "../data/ChatData";
import axios from "axios";
import { SecurityService } from "../service/SecurityService";

interface ChatsState 
{
    currentUser: UserState | null
    chats: ChatData[]
    loading: boolean
};

interface UserState {
    id: number
    email: string
    first_name: string
    last_name: string
    phone: string
    country_number: string
    avatar: string | null
}


class ChatSection extends Component<{}, ChatsState> {
    constructor(props: {}) {
        super(props);
        axios.defaults.headers.common["Authorization"] = SecurityService.getAuthorizationBearerHeader();
        this.state = {currentUser: null, chats: [], loading: true };
    }

    public componentDidMount(): void {
        this.getChats();
        this.getCurrentUser();
    }

    private getChats() {
        axios.post(
            "http://127.0.0.1:8000/api/get/chats",
        ).then((response) => {
            let data = JSON.parse(response.data);
            this.setState({ chats: data });
        })
    }

    private getCurrentUser() {
        axios.post(
            "http://127.0.0.1:8000/api/get/user",
        ).then((response) => {
            let data = JSON.parse(response.data);
            this.setState({ currentUser: data, loading: false });
        })
    }

    render(): React.ReactNode {
        const userChats = this.state.chats;

        return (
            <section className="user-chats-container">
                <div className="current-user">
                    { this.state.currentUser?.first_name } {this.state.currentUser?.last_name}
                </div>
                <div className="chats">
                    {userChats !== null ? userChats.map((chat) => (
                        <div className="chat">
                        <div className="chat-name-container">
                            <div className="chat-name">
                                { chat.name }
                            </div>
                            <div className="chat-message">
                                
                            </div>
                        </div>
                        <div className="chat-message-time">
                            20:23
                        </div>                   
                    </div>
                    )) : null}
                </div>
            </section>
        )
    }
}

export default ChatSection;
