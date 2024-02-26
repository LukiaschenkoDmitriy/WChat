import React, { Component } from "react";
import axios from "axios";

interface ChatsState {
    chats: ChatData[],
    loading: boolean
};

interface Message {
    message: string,
    user: string,
    time: string
}

interface ChatData {
    name: string,
    avatar: string,
    last_message: Message
}

class Chat extends Component<{}, ChatsState> {
    constructor(props: {}) {
        super(props);
        this.state = { chats: [], loading: true };
    }

    public componentDidMount(): void {
        this.getChats();
    }

    private getChats() {
        
    }

    render(): React.ReactNode {
        const loading = this.state.loading;
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row">
                            <h2 className="text-center"><span>List of posts</span>Created with <i
                                className="fa fa-heart"></i> by yemiwebby </h2>
                        </div>
    
                        {loading ? (
                            <div className={'row text-center'}>
                                <span className="fa fa-spin fa-spinner fa-4x"></span>
                            </div>
    
                        ) : (
                            <div className={'row'}>
                               
                            </div>
                        )}
                    </div>
                </section>
            </div>
        );
    }
}

export default Chat;
