import React, { useState } from "react";
import styles from "@chatscope/chat-ui-kit-styles/dist/default/styles.min.css";
import {
  MainContainer,
  ChatContainer,
  MessageList,
  Message,
  MessageInput,
} from "@chatscope/chat-ui-kit-react";

function App() {
  // const [messages, setMessages] = useState([]);
  const [messages, setMessages] = useState([
    {
      message: "Привет, я Сбер! Расскажи про свои хобби",
      sentTime: "just now",
      sender: "SberGigaChat"
    }
  ]);
  const [isTyping, setIsTyping] = useState(false);

  const handleSend = async (message) => {
    const newMessage = {
      message,
      direction: 'outgoing',
      sender: "user"
    };
    const newMessages = [...messages, newMessage];
    setMessages(newMessages);

    setIsTyping(true);
    await processMessageToYandexGPT(newMessages);
  };



  
  async function processMessageToYandexGPT(chatMessages) {
    const message = chatMessages[chatMessages.length - 1].message;
  
    try {
      const response = await fetch("http://localhost/chat/back/web/api/create-mes", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ message }),
      });

      const data = await response.json();
      // console.log(message)
      const botMessage = data.response || "Ошибка в ответе от сервера";
      console.log(data)
  
      setMessages([...chatMessages, {
        message: botMessage,
        sender: "SberGigaChat"
      }]);
    } catch (error) {
      console.error('Ошибка:', error);
      setMessages([...chatMessages, {
        message: "Произошла ошибка при запросе к серверу.",
        sender: "SberGigaChat"
      }]);
    } finally {
      setIsTyping(false);
    }
  }
  

  return (
    <div className="App">
      <div style={{ position:"relative", height: "800px", width: "700px"  }}>
        <MainContainer>
          <ChatContainer>       
            <MessageList scrollBehavior="smooth" >
              {messages.map((message, i) => {
                console.log(message)
                return <Message key={i} model={message} />
              })}
            </MessageList>
            <MessageInput placeholder="Type message here" onSend={handleSend} />        
          </ChatContainer>
        </MainContainer>
      </div>
    </div>
  )
}

export default App;